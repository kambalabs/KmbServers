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

use KmbPuppetDb\Model\NodeInterface;
use KmbServers\Service\NodeCollector;
use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;

class ApiController extends AbstractRestfulController
{
    public function getList()
    {
        /** @var NodeCollector $nodeCollector */
        $nodeCollector = $this->serviceLocator->get('KmbServers\Service\NodeCollector');
        $environmentName = $this->params()->fromRoute('env');

        $params = null;
        if ($environmentName) {
            $params = ['environment' => $environmentName];
        }

        $nodes = [];
        foreach ($nodeCollector->findAll($params) as $node) {
            /** @var NodeInterface $node */
            $nodes[] = [
                'name' => $node->getName(),
                'status' => $node->getStatus(),
                'reportedAt' => $node->getReportedAt()->format(\DateTime::ATOM),
            ];
        }

        return new JsonModel($nodes);
    }

    public function get($id)
    {
        /** @var \KmbPuppetDb\Service\NodeInterface $nodeService */
        $nodeService = $this->serviceLocator->get('KmbPuppetDb\Service\Node');

        $node = $nodeService->getByName($id);
        if ($node === null) {
            return new JsonModel();
        }
        return new JsonModel([
            'name' => $node->getName(),
            'status' => $node->getStatus(),
            'reportedAt' => $node->getReportedAt()->format(\DateTime::ATOM),
            'facts' => $node->getFacts(),
        ]);
    }
}
