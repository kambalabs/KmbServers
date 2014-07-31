<?php
namespace KmbServersTest\Service;

use GtnPersistBase\Infrastructure\Memory\Repository;
use KmbDomain\Model\EnvironmentInterface;
use KmbDomain\Model\EnvironmentRepositoryInterface;
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
    }
}

class FakeEnvironmentRepository extends Repository implements EnvironmentRepositoryInterface
{
    /**
     * @return array
     */
    public function getAllRoots()
    {
    }

    /**
     * @return EnvironmentInterface
     */
    public function getDefault()
    {
    }

    /**
     * @param string $name
     * @return EnvironmentInterface
     */
    public function getRootByName($name)
    {
    }

    /**
     * @param EnvironmentInterface $environment
     * @return array
     */
    public function getAllChildren(EnvironmentInterface $environment)
    {
    }

    /**
     * @param EnvironmentInterface $environment
     * @return EnvironmentInterface
     */
    public function getParent(EnvironmentInterface $environment)
    {
    }
}
