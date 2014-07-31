<?php
return [
    'controller_plugins' => [
        'factories' => [
            'translate' => 'KmbBaseTest\Controller\Plugin\FakeTranslateFactory',
        ],
    ],
    'router' => [
        'routes' => [
            'signout' => [
                'type' => 'Literal',
                'options' => [
                    'route' => '/signout',
                    'defaults' => [
                        'controller' => 'KmbOabAuthentication\Controller\Signout',
                        'action' => 'index',
                    ],
                ],
            ],
            'index' => [
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => [
                    'route' => '/',
                    'defaults' => [
                        'controller' => 'KmbDashboard\Controller\Index',
                        'action' => 'index',
                    ],
                ],
            ],
            'dashboard' => [
                'type' => 'segment',
                'options' => [
                    'route' => '/dashboard[/][:action][/:id]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                    ],
                    'defaults' => [
                        'controller' => 'KmbDashboard\Controller\Index',
                        'action' => 'index',
                    ],
                ],
            ],
            'puppet' => [
                'type' => 'Literal',
                'options' => [
                    'route' => '/puppet',
                    'defaults' => [
                        '__NAMESPACE__' => 'KmbPuppet\Controller',
                        'controller' => 'Reports',
                        'action' => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'default' => [
                        'type' => 'Segment',
                        'options' => [
                            'route' => '/[:controller[/:action]]',
                            'constraints' => [
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ],
                            'defaults' => [
                            ],
                        ],
                    ],
                    'withid' => [
                        'type' => 'Segment',
                        'options' => [
                            'route' => '/[:controller[/:id][/:action]]',
                            'constraints' => [
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'id' => '[0-9]*',
                            ],
                            'defaults' => [
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
];
