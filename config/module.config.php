<?php

namespace Translator;

use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;
use Zend\ServiceManager\Factory\InvokableFactory;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;

return [
    'controllers' => [
        'factories' => [
            Controller\TranslatorController::class => Factory\TranslatorControllerFactory::class,
        ],
        'aliases' => [
            'translatorbeheer' => Controller\TranslatorController::class,
        ],
    ],
    'service_manager' => [
        'invokables' => [
            'Translator\Service\translatorServiceInterface' => 'Translator\Service\translatorService'
        ],
    ],
    'view_helpers' => [
        'factories' => [
            View\Helper\Translate::class => View\Helper\Factory\TranslateFactory::class,
        ],
        'aliases' => [
            'translate' => View\Helper\Translate::class,
        ],
    ],
    // The following section is new and should be added to your file
    'router' => [
        'routes' => [
            'translator' => [
                'type' => 'segment',
                'options' => [
                    'route' => '/translator[/:action][/:id]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                    ],
                    'defaults' => [
                        'controller' => 'translatorbeheer',
                        'action' => 'index',
                    ],
                ],
            ],
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [
            'translator' => __DIR__ . '/../view',
        ],
    ],
    // The 'access_filter' key is used by the User module to restrict or permit
    // access to certain controller actions for unauthorized visitors.
    'access_filter' => [
        'controllers' => [
            'translatorbeheer' => [
                // to anyone.
                ['actions' => '*', 'allow' => '+translator.manage']
            ],
        ]
    ],
    'doctrine' => [
        'driver' => [
            __NAMESPACE__ . '_driver' => [
                'class' => AnnotationDriver::class,
                'cache' => 'array',
                'paths' => [__DIR__ . '/../src/Entities']
            ],
            'orm_default' => [
                'drivers' => [
                    __NAMESPACE__ . '\Entities' => __NAMESPACE__ . '_driver'
                ]
            ]
        ]
    ],
];
