<?php
namespace KmbServersTest\View\Decorator;

use KmbCoreTest\View\Decorator\AbstractDecoratorTestCase;
use KmbPuppetDb\Model\Node;
use KmbPuppetDb\Model\NodeInterface;
use KmbServers\View\Decorator\NodeRAMDecorator;
use KmbServersTest\Bootstrap;

class NodeRAMDecoratorTest extends AbstractDecoratorTestCase
{
    protected function setUp()
    {
        $this->decorator = new NodeRAMDecorator();
        $this->decorator->setViewHelperManager($this->getViewHelperManager(Bootstrap::getServiceManager()));
    }

    /** @test */
    public function canDecorateTitle()
    {
        $this->assertEquals('__ RAM __', $this->decorator->decorateTitle());
    }

    /** @test */
    public function canDecorateValue()
    {
        $node = new Node('node1.local', NodeInterface::UNCHANGED, null, array(
            'memorysize' => '4.00 GB',
        ));
        $this->assertEquals('## 4.00 GB ##', $this->decorator->decorateValue($node));
    }
}
