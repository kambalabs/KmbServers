<?php
namespace ServersTest\View\Decorator;

use KmbCoreTest\View\Decorator\AbstractDecoratorTestCase;
use KmbPuppetDb\Model\Node;
use KmbPuppetDb\Model\NodeInterface;
use KmbServers\View\Decorator\NodeNameDecorator;
use KmbServersTest\Bootstrap;

class NodeNameDecoratorTest extends AbstractDecoratorTestCase
{
    protected function setUp()
    {
        $this->decorator = new NodeNameDecorator();
        $this->decorator->setViewHelperManager($this->getViewHelperManager(Bootstrap::getServiceManager()));
    }

    /** @test */
    public function canDecorateTitle()
    {
        $this->assertEquals('__ Server __ *', $this->decorator->decorateTitle());
    }

    /** @test */
    public function canDecorateValue()
    {
        $node = new Node('node1.local', NodeInterface::UNCHANGED, null, array(
            'hostname' => 'node1',
        ));
        $this->assertEquals('<a href="/servers/node1.local?back=/servers/" class="show-server" data-rel="tooltip" data-placement="right" data-original-title="## node1.local ##">## node1 ##</a>', $this->decorator->decorateValue($node));
    }
}
