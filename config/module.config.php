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
            Controller\LanguageController::class => Factory\LanguageControllerFactory::class,
        ],
        'aliases' => [
            'translatorbeheer' => Controller\TranslatorController::class,
            'languagebeheer' => Controller\LanguageController::class,
        ],
    ],
    'service_manager' => [
        'invokables' => [
            'Translator\Service\translatorServiceInterface' => 'Translator\Service\translatorService',
            'Translator\Service\languageServiceInterface' => 'Translator\Service\languageService',
            'Translator\Service\translationIndexServiceInterface' => 'Translator\Service\translationIndexService'
        ],
    ],
    'view_helpers' => [
        'factories' => [
            View\Helper\Translate::class => View\Helper\Factory\TranslateFactory::class,
        ],
        'aliases' => [
            'translator' => View\Helper\Translate::class,
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
        'language' => [
                'type' => 'segment',
                'options' => [
                    'route' => '/language[/:action][/:id]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                    ],
                    'defaults' => [
                        'controller' => 'languagebeheer',
                        'action' => 'index',
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
            'languagebeheer' => [
                // to anyone.
                ['actions' => '*', 'allow' => '+language.manage']
            ],
        ]
    ],
    'translatorSettings' => [
        'defaultLanguage' => 'ned',
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
