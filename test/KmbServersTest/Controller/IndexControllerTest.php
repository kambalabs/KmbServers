<?php
namespace KmbServersTest\Controller;

use KmbPuppetDb\Model;
use KmbPuppetDbTest\FakeHttpClient;
use KmbServersTest\Bootstrap;
use Zend\Json\Json;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

class IndexControllerTest extends AbstractHttpControllerTestCase
{
    protected $traceError = true;

    public function setUp()
    {
        $this->setApplicationConfig(Bootstrap::getApplicationConfig());
        parent::setUp();
        $serviceManager = $this->getApplicationServiceLocator();
        $serviceManager->setAllowOverride(true);
        $serviceManager->setService('KmbPuppetDb\Http\Client', new FakeHttpClient('2014-03-31'));
    }

    /** @test */
    public function canGetIndex()
    {
        $this->dispatch('/servers');

        $this->assertResponseStatusCode(200);
        $this->assertControllerName('KmbServers\Controller\Index');
        $this->assertQueryContentContains('#servers th', 'OS *');
    }

    /** @test */
    public function canGetIndexInJson()
    {
        $this->getRequest()->getHeaders()->addHeaderLine('Accept', 'application/json');

        $this->dispatch('/servers?draw=1');

        $this->assertResponseStatusCode(200);
        $this->assertControllerName('KmbServers\Controller\Index');
        $response = (array)Json::decode($this->getResponse()->getContent());
        $this->assertEquals(9, count($response['data']));
        $this->assertEquals('Debian', $response['data'][0][1]);
    }

    /** @test */
    public function canGetIndexWithPagingInJson()
    {
        $this->getRequest()->getHeaders()->addHeaderLine('Accept', 'application/json');

        $this->dispatch('/servers?draw=1&start=3&length=5');

        $this->assertResponseStatusCode(200);
        $this->assertControllerName('KmbServers\Controller\Index');
        $response = (array)Json::decode($this->getResponse()->getContent());
        $this->assertEquals(5, count($response['data']));
        $this->assertContains('node4', $response['data'][0][0]);
        $this->assertEquals('Debian', $response['data'][0][1]);
    }

    /** @test */
    public function canShowServer()
    {
        $this->dispatch('/servers/node1.local');

        $this->assertResponseStatusCode(200);
        $this->assertControllerName('KmbServers\Controller\Index');
        $this->assertActionName('show');
        $this->assertQueryContentContains('div.date', 'node1.local');
    }

    /** @test */
    public function canGetFacts()
    {
        $this->dispatch('/servers/node1.local/facts');

        $this->assertResponseStatusCode(200);
        $this->assertControllerName('KmbServers\Controller\Index');
        $this->assertActionName('facts');
        $this->assertEquals(array(
            'data' => array(
                array('uptime_days', '<pre>477</pre>'),
                array('operatingsystem', '<pre>Debian</pre>'),
                array('kernelversion', '<pre>2.6.32</pre>'),
                array('lsbdistcodename', '<pre>wheezy</pre>'),
                array('lsbdistdescription', '<pre>Debian GNU/Linux 7.3 (wheezy)</pre>'),
                array('processorcount', '<pre>4</pre>'),
                array('memorysize', '<pre>2.00 GB</pre>'),
                array('uptime', '<pre>477 days</pre>'),
                array('pf', '<pre>TEST</pre>'),
                array('hostname', '<pre>node1</pre>'),
            )
        ), (array)Json::decode($this->getResponse()->getContent()));
    }
}
