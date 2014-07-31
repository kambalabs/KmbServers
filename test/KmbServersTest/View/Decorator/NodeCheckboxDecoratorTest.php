<?php
namespace ServersTest\View\Decorator;

use KmbBaseTest\View\Decorator\AbstractDecoratorTestCase;
use KmbPuppetDb\Model\Node;
use KmbServers\View\Decorator\NodeCheckboxDecorator;
use KmbServersTest\Bootstrap;

class NodeCheckboxDecoratorTest extends AbstractDecoratorTestCase
{
    protected function setUp()
    {
        $this->decorator = new NodeCheckboxDecorator();
        $this->decorator->setViewHelperManager($this->getViewHelperManager(Bootstrap::getServiceManager()));
    }

    /** @test */
    public function canDecorateTitle()
    {
        $this->assertEquals('<input type="checkbox" name="select-all-nodes" id="select-all-nodes" />', $this->decorator->decorateTitle());
    }

    /** @test */
    public function canDecorateValue()
    {
        $node = new Node('node1.local');
        $this->assertEquals('<input type="checkbox" name="nodes[]" class="select-node" value="node1.local" />', $this->decorator->decorateValue($node));
    }
}
