<?php
namespace KmbServersTest\Controller;

use GtnDataTables\Model\Collection;
use KmbPuppetDb\Model\Node;
use KmbServersTest\Bootstrap;
use Zend\Json\Json;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

class ApiControllerTest extends AbstractHttpControllerTestCase
{
    public function setUp()
    {
        $this->setApplicationConfig(Bootstrap::getApplicationConfig());
        parent::setUp();

        $serviceManager = $this->getApplicationServiceLocator();
        $serviceManager->setAllowOverride(true);

        $nodeCollector = $this->getMock('KmbServers\Service\NodeCollector');
        $nodeCollector->expects($this->any())
            ->method('findAll')
            ->will($this->returnCallback(function ($params) {
                $nodes = [
                    new Node('node1.local', 'unchanged', new \DateTime('2015-05-20 10:12:58')),
                    new Node('node2.local', 'unchanged', new \DateTime('2015-05-20 10:13:21')),
                    new Node('node3.local', 'failed', new \DateTime('2015-05-20 10:12:47'))
                ];
                if (empty($params)) {
                    return Collection::factory($nodes);
                }
                return Collection::factory(array_slice($nodes, 0, 1));
            }));
        $serviceManager->setService('KmbServers\Service\NodeCollector', $nodeCollector);

        $nodeService = $this->getMock('KmbPuppetDb\Service\Node');
        $nodeService->expects($this->any())
            ->method('getByName')
            ->will($this->returnCallback(function ($name) {
                return new Node($name, 'unchanged', new \DateTime('2015-05-20 10:12:58'), ['operatingsystem' => 'Debian']);
            }));
        $serviceManager->setService('KmbPuppetDb\Service\Node', $nodeService);
    }

    /** @test */
    public function canGetIndex()
    {
        $this->dispatch('/api/servers?facts[]=operatingsystem&facts[]=kernelversion');

        $this->assertResponseStatusCode(200);
        $this->assertControllerName('KmbServers\Controller\Api');
        $this->assertActionName('getList');
        $response = Json::decode($this->getResponse()->getContent(), Json::TYPE_ARRAY);
        $this->assertEquals([
            ['name' => 'node1.local', 'status' => 'unchanged', 'reportedAt' => '2015-05-20T10:12:58+02:00'],
            ['name' => 'node2.local', 'status' => 'unchanged', 'reportedAt' => '2015-05-20T10:13:21+02:00'],
            ['name' => 'node3.local', 'status' => 'failed', 'reportedAt' => '2015-05-20T10:12:47+02:00'],
        ], $response);
    }

    /** @test */
    public function canGetIndexForSpecifiedEnvironment()
    {
        $this->dispatch('/api/env/STABLE/servers');

        $this->assertResponseStatusCode(200);
        $this->assertControllerName('KmbServers\Controller\Api');
        $this->assertActionName('getList');
        $response = Json::decode($this->getResponse()->getContent(), Json::TYPE_ARRAY);
        $this->assertEquals([['name' => 'node1.local', 'status' => 'unchanged', 'reportedAt' => '2015-05-20T10:12:58+02:00']], $response);
    }

    /** @test */
    public function canGetServer()
    {
        $this->dispatch('/api/server/node1.local');

        $this->assertResponseStatusCode(200);
        $this->assertControllerName('KmbServers\Controller\Api');
        $this->assertActionName('get');
        $response = Json::decode($this->getResponse()->getContent(), Json::TYPE_ARRAY);
        $this->assertEquals([
            'name' => 'node1.local',
            'status' => 'unchanged',
            'reportedAt' => '2015-05-20T10:12:58+02:00',
            'facts' => [
                'operatingsystem' => 'Debian',
            ],
        ], $response);
    }
}
