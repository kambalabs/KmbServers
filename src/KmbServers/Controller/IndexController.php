<?php
/**
 * @copyright Copyright (c) 2014 Orange Applications for Business
 * @link      http://github.com/kambalabs for the sources repositories
 *
 * This file is part of Kamba.
 *
 * Kamba is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * (at your option) any later version.
 *
 * Kamba is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Kamba.  If not, see <http://www.gnu.org/licenses/>.
 */
namespace KmbServers\Controller;

use GtnDataTables\Service\DataTable;
use KmbAuthentication\Controller\AuthenticatedControllerInterface;
use KmbDomain\Model\EnvironmentInterface;
use KmbDomain\Model\EnvironmentRepositoryInterface;
use KmbPuppetDb\Service\NodeInterface;
use Zend\Log\Logger;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController implements AuthenticatedControllerInterface
{
    /** @var EnvironmentRepositoryInterface */
    protected $environmentRepository;

    /** @var NodeInterface */
    protected $nodeService;

    /** @var Logger */
    protected $logger;

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

        $variables = [];
        if ($viewModel instanceof JsonModel) {
            /** @var DataTable $datatable */
            $datatable = $this->getServiceLocator()->get('servers_datatable');
            $params = $this->params()->fromQuery();
            $environment = $this->environmentRepository->getById($this->params()->fromRoute('envId'));
            if ($environment !== null) {
                $params['environment'] = $environment;
            }
            $result = $datatable->getResult($params);
            $variables = [
                'draw' => $result->getDraw(),
                'recordsTotal' => $result->getRecordsTotal(),
                'recordsFiltered' => $result->getRecordsFiltered(),
                'data' => $result->getData(),
            ];
        }

        return $viewModel->setVariables($variables);
    }

    public function factNamesAction()
    {
        $escapeHtml = $this->getServiceLocator()->get('ViewHelperManager')->get('escapeHtml');
        return new JsonModel([
            'facts' => array_map(function ($fact) use ($escapeHtml) {
                return $escapeHtml($fact);
            }, $this->getServiceLocator()->get('KmbPuppetDb\Service\FactNames')->getAll())
        ]);
    }

    public function showAction()
    {
        $environment = $this->environmentRepository->getById($this->params()->fromRoute('envId'));
        $node = $this->nodeService->getByName($this->params('hostname'));
        $model = new ViewModel([
            'node' => $node,
            'environment' => $environment,
            'back' => $this->params()->fromQuery('back'),
        ]);
        $this->widget('serverInfoBar')->runActions($model);
        $this->widget('serverTabTitle')->runActions($model);
        $this->widget('serverTabContent')->runActions($model);
        return $model;
    }

    public function factsAction()
    {
        $node = $this->nodeService->getByName($this->params('hostname'));
        $escapeHtml = $this->getServiceLocator()->get('viewhelpermanager')->get('escapeHtml');
        $data = array();
        foreach ($node->getFacts() as $fact => $value) {
            $data[] = array($escapeHtml($fact), '<pre>' . $escapeHtml($value) . '</pre>');
        }
        return new JsonModel(array('data' => $data));
    }

    public function assignToEnvironmentAction()
    {
        /** @var EnvironmentInterface $environment */
        $environment = $this->environmentRepository->getById($this->params()->fromPost('environment'));

        if ($environment === null) {
            return $this->notFoundAction();
        }

        foreach ($this->params()->fromPost('nodes', []) as $nodeName) {
            $node = $this->nodeService->getByName($nodeName);
            if ($node !== null) {
                $node->setEnvironment($environment->getNormalizedName());
                $this->nodeService->replaceFacts($node);
            }
        }

        $this->flashMessenger()->addSuccessMessage(sprintf($this->translate('The servers has been succesfully assigned to environment %s'), $environment->getNormalizedName()));
        return $this->redirect()->toRoute('servers', ['action' => 'index'], [], true);
    }

    /**
     * Set EnvironmentRepository.
     *
     * @param \KmbDomain\Model\EnvironmentRepositoryInterface $environmentRepository
     * @return IndexController
     */
    public function setEnvironmentRepository($environmentRepository)
    {
        $this->environmentRepository = $environmentRepository;
        return $this;
    }

    /**
     * Get EnvironmentRepository.
     *
     * @return \KmbDomain\Model\EnvironmentRepositoryInterface
     */
    public function getEnvironmentRepository()
    {
        return $this->environmentRepository;
    }

    /**
     * Set NodeService.
     *
     * @param \KmbPuppetDb\Service\NodeInterface $nodeService
     * @return IndexController
     */
    public function setNodeService($nodeService)
    {
        $this->nodeService = $nodeService;
        return $this;
    }

    /**
     * Get NodeService.
     *
     * @return \KmbPuppetDb\Service\NodeInterface
     */
    public function getNodeService()
    {
        return $this->nodeService;
    }

    /**
     * Set Logger.
     *
     * @param \Zend\Log\Logger $logger
     * @return IndexController
     */
    public function setLogger($logger)
    {
        $this->logger = $logger;
        return $this;
    }

    /**
     * Get Logger.
     *
     * @return \Zend\Log\Logger
     */
    public function getLogger()
    {
        return $this->logger;
    }

    /**
     * @param string $message
     * @return IndexController
     */
    public function debug($message)
    {
        if ($this->logger != null) {
            $this->logger->debug($message);
        }
        return $this;
    }
}
