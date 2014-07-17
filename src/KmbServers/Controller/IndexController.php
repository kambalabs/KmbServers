<?php
/**
 * @copyright Copyright (c) 2014 Orange Applications for Business
 * @link      http://github.com/multimediabs/kamba for the canonical source repository
 *
 * This file is part of kamba.
 *
 * kamba is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * (at your option) any later version.
 *
 * kamba is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with kamba.  If not, see <http://www.gnu.org/licenses/>.
 */
namespace KmbServers\Controller;

use GtnDataTables\Service\DataTable;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
        $viewModel = $this->acceptableViewModelSelector(array(
            'Zend\View\Model\ViewModel' => array(
                'text/html',
            ),
            'Zend\View\Model\JsonModel' => array(
                'application/json',
            ),
        ));

        /** @var DataTable $datatable */
        $datatable = $this->getServiceLocator()->get('servers_datatable');
        if ($viewModel instanceof JsonModel) {
            $result = $datatable->getResult($this->params()->fromQuery());
            $viewModel->setVariable('draw', $result->getDraw());
            $viewModel->setVariable('recordsTotal', $result->getRecordsTotal());
            $viewModel->setVariable('recordsFiltered', $result->getRecordsFiltered());
            $viewModel->setVariable('data', $result->getData());
        } else {
            $viewModel->setVariable('facts', $this->getServiceLocator()->get('KmbPuppetDb\Service\FactNames')->getAll());
        }

        return $viewModel;
    }

    public function showAction()
    {
        $node = $this->getServiceLocator()->get('KmbPuppetDb\Service\Node')->getByName($this->params('hostname'));
        return new ViewModel(array(
            'node' => $node,
            'back' => $this->params()->fromQuery('back'),
        ));
    }

    public function factsAction()
    {
        $node = $this->getServiceLocator()->get('KmbPuppetDb\Service\Node')->getByName($this->params('hostname'));
        $escapeHtml = $this->getServiceLocator()->get('viewhelpermanager')->get('escapeHtml');
        $data = array();
        foreach ($node->getFacts() as $fact => $value) {
            $data[] = array($escapeHtml($fact), '<pre>' . $escapeHtml($value) . '</pre>');
        }
        return new JsonModel(array('data' => $data));
    }
}
