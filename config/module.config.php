<?php
return [
    'router' => [
        'routes' => [
            'servers' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '[/env/:envId]/servers[/[:action]]',
                    'constraints' => [
                        'envId' => '[0-9]+',
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ],
                    'defaults' => [
                        '__NAMESPACE__' => 'KmbServers\Controller',
                        'controller' => 'Index',
                        'action' => 'index',
                        'envId' => '0',
                    ],
                ],
            ],
            'server' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '[/env/:envId]/server/:hostname[/[:action]]',
                    'constraints' => [
                        'envId' => '[0-9]+',
                        'hostname' => '(([a-zA-Z0-9]|[a-zA-Z0-9][a-zA-Z0-9\-]*[a-zA-Z0-9])\.)*([A-Za-z0-9]|[A-Za-z0-9][A-Za-z0-9\-]*[A-Za-z0-9])',
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ],
                    'defaults' => [
                        '__NAMESPACE__' => 'KmbServers\Controller',
                        'controller' => 'Index',
                        'action' => 'show',
                        'envId' => '0',
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
        'factories' => [
            'KmbServers\Controller\Index' => 'KmbServers\Service\IndexControllerFactory'
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
                    'actions' => ['index', 'show', 'facts', 'fact-names'],
                    'roles' => ['user']
                ],
                [
                    'controller' => 'KmbServers\Controller\Index',
                    'actions' => ['assign-to-environment'],
                    'roles' => ['admin']
                ],
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
                    'decorator' => 'KmbServers\View\Decorator\NodeCheckboxDecorator',
                ],
                [
                    'decorator' => 'KmbServers\View\Decorator\NodeNameDecorator',
                    'key' => 'name',
                ],
                [
                    'decorator' => 'KmbServers\View\Decorator\NodeEnvironmentDecorator',
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
    'asset_manager' => [
        'resolver_configs' => [
            'paths' => [
                __DIR__ . '/../public',
            ],
        ],
    ],
];
