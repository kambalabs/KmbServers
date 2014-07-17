<?php
namespace KmbServersTest\Service;

use KmbServers\Service\NodeCollector;
use KmbServers\Service\NodeCollectorFactory;
use KmbServersTest\Bootstrap;

class NodeCollectorFactoryTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    public function canCreateService()
    {
        $factory = new NodeCollectorFactory();

        /** @var NodeCollector $service */
        $service = $factory->createService(Bootstrap::getServiceManager());

        $this->assertInstanceOf('KmbServers\Service\NodeCollector', $service);
        $this->assertInstanceOf('KmbPuppetDb\Service\Node', $service->getNodeService());
    }
}
