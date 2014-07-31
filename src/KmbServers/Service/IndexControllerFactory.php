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
namespace KmbServers\Service;

use KmbDomain\Model\EnvironmentRepositoryInterface;
use KmbPuppetDb\Service\NodeInterface;
use KmbServers\Controller\IndexController;
use Zend\Mvc\Controller\ControllerManager;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class IndexControllerFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return IndexController
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $controller = new IndexController();

        /** @var ControllerManager $serviceLocator */
        $serviceManager = $serviceLocator->getServiceLocator();

        /** @var EnvironmentRepositoryInterface $environmentRepository */
        $environmentRepository = $serviceManager->get('EnvironmentRepository');
        $controller->setEnvironmentRepository($environmentRepository);

        /** @var NodeInterface $nodeService */
        $nodeService = $serviceManager->get('KmbPuppetDb\Service\Node');
        $controller->setNodeService($nodeService);

        return $controller;
    }
}
