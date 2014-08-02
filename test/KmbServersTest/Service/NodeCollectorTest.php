<?php
namespace KmbServersTest\Service;

use KmbDomain\Model\Environment;
use KmbPuppetDb\Model;
use KmbPuppetDb\Model\NodesCollection;
use KmbServers\Service\NodeCollector;

class NodeCollectorTest extends \PHPUnit_Framework_TestCase
{
    /** @var NodeCollector */
    protected $nodeCollector;

    protected function setUp()
    {
        $this->nodeCollector = new NodeCollector();
        $nodeService = $this->getMock('KmbPuppetDb\Service\Node');
        $nodeService->expects($this->any())
            ->method('getAll')
            ->will($this->returnCallback(function ($query = null, $offset = null, $limit = null, $orderBy = null) {
                $nodes = [
                    new Model\Node('node1.local'),
                    new Model\Node('node2.local'),
                    new Model\Node('node3.local'),
                    new Model\Node('node4.local'),
                    new Model\Node('node5.local'),
                    new Model\Node('node6.local'),
                    new Model\Node('node7.local'),
                    new Model\Node('node8.local'),
                    new Model\Node('node9.local'),
                ];
                if ($query == [
                        'and',
                        [
                            'or',
                            [
                                '~',
                                ['fact', 'hostname'],
                                'wheezy'
                            ],
                            [
                                '~',
                                ['fact', Model\NodeInterface::ENVIRONMENT_FACT],
                                'wheezy'
                            ],
                            [
                                '~',
                                ['fact', 'operatingsystem'],
                                'wheezy'
                            ],
                            [
                                '~',
                                ['fact', 'kernelversion'],
                                'wheezy'
                            ],
                            [
                                '~',
                                ['fact', 'lsbdistcodename'],
                                'wheezy'
                            ],
                        ],
                        [
                            '~',
                            ['fact', 'architecture'],
                            'amd64'
                        ],
                    ]
                ) {
                    return NodesCollection::factory(
                        [
                            new Model\Node('node3.local'),
                            new Model\Node('node4.local'),
                            new Model\Node('node5.local'),
                            new Model\Node('node7.local'),
                            new Model\Node('node9.local'),
                        ],
                        6,
                        6
                    );
                }
                if ($query == [
                        '~',
                        ['fact', 'architecture'],
                        'amd64'
                    ]
                ) {
                    return NodesCollection::factory(
                        [
                            new Model\Node('node3.local'),
                            new Model\Node('node4.local'),
                            new Model\Node('node5.local'),
                            new Model\Node('node7.local'),
                            new Model\Node('node8.local'),
                        ],
                        7,
                        7
                    );
                }
                if ($query == [
                        '=',
                        ['fact', 'kmbenv'],
                        'STABLE_PF1'
                    ]
                ) {
                    return NodesCollection::factory(
                        [
                            new Model\Node('node3.local'),
                            new Model\Node('node8.local'),
                        ],
                        2,
                        2
                    );
                }
                if ($query != null) {
                    return NodesCollection::factory(
                        [
                            new Model\Node('node2.local'),
                            new Model\Node('node3.local'),
                            new Model\Node('node4.local'),
                            new Model\Node('node5.local'),
                            new Model\Node('node7.local'),
                        ],
                        8,
                        8
                    );
                }
                if ($orderBy != null) {
                    usort($nodes, function (Model\Node $a, Model\Node $b) {
                        if ($a->getName() === $b->getName()) {
                            return 0;
                        }
                        if ($a->getName() > $b->getName()) {
                            return -1;
                        }
                        return 1;
                    });
                    return NodesCollection::factory(
                        array_slice($nodes, $offset, $limit),
                        count($nodes),
                        count($nodes)
                    );
                }
                return NodesCollection::factory(
                    array_slice($nodes, $offset, $limit),
                    count($nodes),
                    count($nodes)
                );
            }));
        $this->nodeCollector->setNodeService($nodeService);
    }

    /** @test */
    public function canFindAll()
    {
        $collection = $this->nodeCollector->findAll([
            'start' => 0,
            'length' => 5,
        ]);

        $this->assertInstanceOf('GtnDataTables\Model\Collection', $collection);
        $this->assertEquals(5, count($collection->getData()));
        $this->assertEquals(9, $collection->getTotal());
        $this->assertEquals(9, $collection->getFilteredCount());
    }

    /** @test */
    public function canFindAllByEnvironment()
    {
        $parent = new Environment();
        $parent->setName('STABLE');
        $environment = new Environment();
        $environment->setName('PF1');
        $environment->setParent($parent);
        $collection = $this->nodeCollector->findAll([
            'start' => 0,
            'length' => 5,
            'environment' => $environment
        ]);

        $this->assertInstanceOf('GtnDataTables\Model\Collection', $collection);
        $this->assertEquals(2, count($collection->getData()));
        $this->assertEquals(2, $collection->getTotal());
        $this->assertEquals(2, $collection->getFilteredCount());
    }

    /** @test */
    public function canFindAllWithSearch()
    {
        $collection = $this->nodeCollector->findAll([
            'start' => 0,
            'length' => 5,
            'search' => [
                'value' => 'wheezy'
            ]
        ]);

        $this->assertInstanceOf('GtnDataTables\Model\Collection', $collection);
        $data = $collection->getData();
        $this->assertEquals(5, count($data));
        $this->assertEquals('node2.local', $data[0]->getName());
        $this->assertEquals(8, $collection->getTotal());
        $this->assertEquals(8, $collection->getFilteredCount());
    }

    /** @test */
    public function canFindAllWithSearchAndFactFilter()
    {
        $collection = $this->nodeCollector->findAll([
            'start' => 0,
            'length' => 5,
            'search' => [
                'value' => 'wheezy'
            ],
            'factName' => 'architecture',
            'factValue' => 'amd64',
        ]);

        $this->assertInstanceOf('GtnDataTables\Model\Collection', $collection);
        $data = $collection->getData();
        $this->assertEquals(5, count($data));
        $this->assertEquals('node3.local', $data[0]->getName());
        $this->assertEquals(6, $collection->getTotal());
        $this->assertEquals(6, $collection->getFilteredCount());
    }

    /** @test */
    public function canFindAllWithFactFilter()
    {
        $collection = $this->nodeCollector->findAll([
            'start' => 0,
            'length' => 5,
            'factName' => 'architecture',
            'factValue' => 'amd64',
        ]);

        $this->assertInstanceOf('GtnDataTables\Model\Collection', $collection);
        $data = $collection->getData();
        $this->assertEquals(5, count($data));
        $this->assertEquals('node3.local', $data[0]->getName());
        $this->assertEquals(7, $collection->getTotal());
        $this->assertEquals(7, $collection->getFilteredCount());
    }

    /** @test */
    public function canFindAllWithOrdering()
    {
        $collection = $this->nodeCollector->findAll([
            'start' => 0,
            'length' => 5,
            'order' => [
                [
                    'column' => 'name',
                    'dir' => 'desc',
                ],
            ]
        ]);

        $this->assertInstanceOf('GtnDataTables\Model\Collection', $collection);
        $data = $collection->getData();
        $this->assertEquals(5, count($data));
        $this->assertEquals('node9.local', $data[0]->getName());
        $this->assertEquals(9, $collection->getTotal());
        $this->assertEquals(9, $collection->getFilteredCount());
    }
}
