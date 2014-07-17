<?php
namespace KmbServersTest\View\Decorator;

use KmbCoreTest\View\Decorator\AbstractDecoratorTestCase;
use KmbPuppetDb\Model\Node;
use KmbPuppetDb\Model\NodeInterface;
use KmbServers\View\Decorator\NodePuppetDecorator;
use KmbServersTest\Bootstrap;

class NodePuppetDecoratorTest extends AbstractDecoratorTestCase
{
    protected function setUp()
    {
        $this->decorator = new NodePuppetDecorator();
        $this->decorator->setViewHelperManager($this->getViewHelperManager(Bootstrap::getServiceManager()));
    }

    /** @test */
    public function canDecorateTitle()
    {
        $this->assertEquals('__ Puppet __', $this->decorator->decorateTitle());
    }

    /** @test */
    public function canDecorateValue()
    {
        $node = new Node('node1.local', NodeInterface::UNCHANGED, new \DateTime('2014-01-31T10:00:00'));
        $this->assertEquals('<button class="btn btn-xs btn-primary label-uniform-large puppet-reports">> 24h</button>', $this->decorator->decorateValue($node));
    }
}
