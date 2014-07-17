<?php
namespace KmbServersTest\View\Decorator;

use KmbCoreTest\View\Decorator\AbstractDecoratorTestCase;
use KmbPuppetDb\Model\Node;
use KmbPuppetDb\Model\NodeInterface;
use KmbServers\View\Decorator\NodeCPUDecorator;
use KmbServersTest\Bootstrap;

class NodeCPUDecoratorTest extends AbstractDecoratorTestCase
{
    protected function setUp()
    {
        $this->decorator = new NodeCPUDecorator();
        $this->decorator->setViewHelperManager($this->getViewHelperManager(Bootstrap::getServiceManager()));
    }

    /** @test */
    public function canDecorateTitle()
    {
        $this->assertEquals('__ CPU __', $this->decorator->decorateTitle());
    }

    /** @test */
    public function canDecorateValue()
    {
        $node = new Node('node1.local', NodeInterface::UNCHANGED, null, array(
            'processorcount' => '4',
        ));
        $this->assertEquals('## 4 ##', $this->decorator->decorateValue($node));
    }
}
