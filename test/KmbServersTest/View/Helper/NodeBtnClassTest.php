<?php
namespace KmbServersTest\View\Helper;

use KmbPuppetDb\Model;
use KmbServers\View\Helper\NodeBtnClass;

class NodeBtnClassTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    public function canGetUnchangedNodeBtnClass()
    {
        $node = new Model\Node('node1.local', Model\NodeInterface::UNCHANGED);
        $helper = new NodeBtnClass();

        $this->assertEquals('btn-success', $helper($node));
    }

    /** @test */
    public function canGetChangedNodeBtnClass()
    {
        $node = new Model\Node('node1.local', Model\NodeInterface::CHANGED);
        $helper = new NodeBtnClass();

        $this->assertEquals('btn-warning', $helper($node));
    }

    /** @test */
    public function canGetFailedNodeBtnClass()
    {
        $node = new Model\Node('node1.local', Model\NodeInterface::FAILED);
        $helper = new NodeBtnClass();

        $this->assertEquals('btn-danger', $helper($node));
    }

    /** @test */
    public function canGetNullStatusNodeBtnClass()
    {
        $node = new Model\Node('node1.local');
        $helper = new NodeBtnClass();

        $this->assertEquals('btn-primary', $helper($node));
    }

    /** @test */
    public function canGetAtLeast24HoursOldNodeReportTimeBtnClass()
    {
        $now = new \DateTime();
        $node = new Model\Node('node1.local', Model\NodeInterface::UNCHANGED);
        $node->setReportedAt($now->sub(\DateInterval::createFromDateString('1 day')));
        $helper = new NodeBtnClass();

        $this->assertEquals('btn-primary', $helper($node));
    }
}
