<?php
return [
    'controller_plugins' => [
        'invokables' => [
            'writeLog' => 'KmbServersTest\Controller\Plugin\WriteLog',
        ],
        'factories' => [
            'translate' => 'KmbBaseTest\Controller\Plugin\FakeTranslateFactory',
            'translatePlural' => 'KmbBaseTest\Controller\Plugin\FakeTranslatePluralFactory',
        ],
    ],
];
