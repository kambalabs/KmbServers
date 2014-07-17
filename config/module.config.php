<?php
return [
    'router' => [
        'routes' => [
            'servers' => [
                'type' => 'segment',
                'options' => [
                    'route' => '/servers[/]',
                    'defaults' => [
                        'controller' => 'KmbServers\Controller\Index',
                        'action' => 'index',
                    ],
                ],
            ],
            'server' => [
                'type' => 'segment',
                'options' => [
                    'route' => '/servers/:hostname[/:action]',
                    'constraints' => [
                        'hostname' => '(([a-zA-Z0-9]|[a-zA-Z0-9][a-zA-Z0-9\-]*[a-zA-Z0-9])\.)*([A-Za-z0-9]|[A-Za-z0-9][A-Za-z0-9\-]*[A-Za-z0-9])',
                    ],
                    'defaults' => [
                        'controller' => 'KmbServers\Controller\Index',
                        'action' => 'show',
                    ],
                ],
            ],
        ],
    ],
    'translator' => [
        'translation_file_patterns' => [
            [
                'type' => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern' => '%s.mo',
            ],
        ],
    ],
    'controllers' => [
        'invokables' => [
            'KmbServers\Controller\Index' => 'KmbServers\Controller\IndexController'
        ],
    ],
    'view_helpers' => [
        'invokables' => [
            'formatNodeReportTime' => 'KmbServers\View\Helper\FormatNodeReportTime',
            'nodeBtnClass' => 'KmbServers\View\Helper\NodeBtnClass',
        ],
    ],
    'view_manager' => [
        'strategies' => [
            'ViewJsonStrategy',
        ],
        'template_map' => [
            'kmb-servers/index/index' => __DIR__ . '/../view/kmb-servers/index/index.phtml',
            'kmb-servers/index/search-by-fact' => __DIR__ . '/../view/kmb-servers/index/search-by-fact.phtml',
        ],
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
    'zfc_rbac' => [
        'guards' => [
            'ZfcRbac\Guard\ControllerGuard' => [
                [
                    'controller' => 'KmbServers\Controller\Index',
                    'actions' => ['index', 'show', 'facts'],
                    'roles' => ['user']
                ]
            ]
        ],
    ],
    'datatables' => [
        'servers_datatable' => [
            'id' => 'servers',
            'classes' => ['table', 'table-striped', 'table-hover', 'table-condensed', 'bootstrap-datatable'],
            'collectorFactory' => 'KmbServers\Service\NodeCollectorFactory',
            'columns' => [
                [
                    'decorator' => 'KmbServers\View\Decorator\NodeNameDecorator',
                    'key' => 'name',
                ],
                [
                    'decorator' => 'KmbServers\View\Decorator\NodeOSDecorator',
                ],
                [
                    'decorator' => 'KmbServers\View\Decorator\NodeKernelDecorator',
                ],
                [
                    'decorator' => 'KmbServers\View\Decorator\NodeDistribDecorator',
                ],
                [
                    'decorator' => 'KmbServers\View\Decorator\NodeCPUDecorator',
                ],
                [
                    'decorator' => 'KmbServers\View\Decorator\NodeRAMDecorator',
                ],
                [
                    'decorator' => 'KmbServers\View\Decorator\NodeUptimeDecorator',
                ],
                [
                    'decorator' => 'KmbServers\View\Decorator\NodePuppetDecorator',
                    'key' => 'facts-timestamp',
                ],
            ]
        ]
    ],
];
