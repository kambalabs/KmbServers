<?php
namespace KmbServersTest\Service;

use KmbServers\Service\IndexControllerFactory;
use KmbServersTest\Bootstrap;
use Zend\Mvc\Controller\ControllerManager;

class IndexControllerFactoryTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    public function canCreateService()
    {
        /** @var ControllerManager $controllerManager */
        $controllerManager = Bootstrap::getServiceManager()->get('ControllerManager');
        $factory = new IndexControllerFactory();

        $controller = $factory->createService($controllerManager);

        $this->assertInstanceOf('KmbServers\Controller\IndexController', $controller);
        $this->assertInstanceOf('KmbDomain\Model\EnvironmentRepositoryInterface', $controller->getEnvironmentRepository());
        $this->assertInstanceOf('KmbPuppetDb\Service\Node', $controller->getNodeService());
        $this->assertInstanceOf('Zend\Log\Logger', $controller->getLogger());
    }
}
