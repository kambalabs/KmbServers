<?php
namespace KmbServersTest\View\Helper;

use KmbPuppetDb\Model;
use KmbServers\View\Helper\FormatNodeReportTime;

class FormatNodeReportTimeTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    public function canFormatNullNodeReportTime()
    {
        $node = new Model\Node();
        $helper = new FormatNodeReportTime();

        $this->assertEquals('??:??:??', $helper($node));
    }

    /** @test */
    public function canFormatNodeReportTime()
    {
        $node = new Model\Node();
        $node->setReportedAt(new \DateTime('10:28:32'));
        $helper = new FormatNodeReportTime();

        $this->assertEquals('10:28:32', $helper($node));
    }

    /** @test */
    public function canFormatAtLeast24HoursOldNodeReportTime()
    {
        $now = new \DateTime();
        $node = new Model\Node();
        $node->setReportedAt($now->sub(\DateInterval::createFromDateString('1 day')));
        $helper = new FormatNodeReportTime();

        $this->assertEquals('> 24h', $helper($node));
    }
}
