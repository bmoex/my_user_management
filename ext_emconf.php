<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'My Backend User Management',
    'description' => 'A module that makes it possible for editors to maintain backend users.',
    'category' => 'module',
    'version' => '2.3.0',
    'state' => 'stable',
    'uploadFolder' => false,
    'clearCacheOnLoad' => true,
    'author' => 'Benjamin Serfhos',
    'author_email' => 'benjamin@serfhos.com',
    'author_company' => 'Rotterdam School of Management, Erasmus University',
    'constraints' => [
        'depends' => [
            'typo3' => '7.6.0-8.99.99',
            'beuser' => '7.6.0-8.99.99',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
    'autoload' => [
        'psr-4' => [
            'KoninklijkeCollective\\MyUserManagement\\' => 'Classes'
        ]
    ],
];
