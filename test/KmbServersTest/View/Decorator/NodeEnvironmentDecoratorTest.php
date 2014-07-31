<?php
namespace KmbServersTest\View\Decorator;

use KmbBaseTest\View\Decorator\AbstractDecoratorTestCase;
use KmbPuppetDb\Model\Node;
use KmbPuppetDb\Model\NodeInterface;
use KmbServers\View\Decorator\NodeEnvironmentDecorator;
use KmbServersTest\Bootstrap;

class NodeEnvironmentDecoratorTest extends AbstractDecoratorTestCase
{
    protected function setUp()
    {
        $this->decorator = new NodeEnvironmentDecorator();
        $this->decorator->setViewHelperManager($this->getViewHelperManager(Bootstrap::getServiceManager()));
    }

    /** @test */
    public function canDecorateTitle()
    {
        $this->assertEquals('__ Environment __ *', $this->decorator->decorateTitle());
    }

    /** @test */
    public function canDecorateValue()
    {
        $node = new Node('node1.local', NodeInterface::UNCHANGED, null, [], 'STABLE_PF1');
        $this->assertEquals('## STABLE_PF1 ##', $this->decorator->decorateValue($node));
    }
}
