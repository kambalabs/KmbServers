<?php
namespace KmbServersTest\View\Decorator;

use KmbCoreTest\View\Decorator\AbstractDecoratorTestCase;
use KmbPuppetDb\Model\Node;
use KmbPuppetDb\Model\NodeInterface;
use KmbServers\View\Decorator\NodeKernelDecorator;
use KmbServersTest\Bootstrap;

class NodeKernelDecoratorTest extends AbstractDecoratorTestCase
{
    protected function setUp()
    {
        $this->decorator = new NodeKernelDecorator();
        $this->decorator->setViewHelperManager($this->getViewHelperManager(Bootstrap::getServiceManager()));
    }

    /** @test */
    public function canDecorateTitle()
    {
        $this->assertEquals('__ Kernel __ *', $this->decorator->decorateTitle());
    }

    /** @test */
    public function canDecorateValue()
    {
        $node = new Node('node1.local', NodeInterface::UNCHANGED, null, array(
            'kernelversion' => '3.2.0',
        ));
        $this->assertEquals('## 3.2.0 ##', $this->decorator->decorateValue($node));
    }
}
