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
            Controller\TranslationController::class => Factory\TranslationControllerFactory::class,
        ],
        'aliases' => [
            'translatorbeheer' => Controller\TranslatorController::class,
            'languagebeheer' => Controller\LanguageController::class,
            'translationbeheer' => Controller\TranslationController::class,
        ],
    ],
    'service_manager' => [
        'invokables' => [
            'Translator\Service\translatorServiceInterface' => 'Translator\Service\translatorService',
            'Translator\Service\translationServiceInterface' => 'Translator\Service\translationService',
            'Translator\Service\languageServiceInterface' => 'Translator\Service\languageService',
            'Translator\Service\translationIndexServiceInterface' => 'Translator\Service\translationIndexService'
        ],
    ],
    'view_helpers' => [
        'factories' => [
            View\Helper\Translate::class => View\Helper\Factory\TranslateFactory::class,
            View\Helper\LanguageSelect::class => View\Helper\Factory\LanguageSelectFactory::class,
        ],
        'aliases' => [
            'translator' => View\Helper\Translate::class,
            'languageSelect' => View\Helper\LanguageSelect::class,
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
            'translation' => [
                'type' => 'segment',
                'options' => [
                    'route' => '/translation[/:action][/:id]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                    ],
                    'defaults' => [
                        'controller' => 'translationbeheer',
                        'action' => 'index',
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
            'translationbeheer' => [
                // to anyone.
                ['actions' => '*', 'allow' => '+translation.manage']
            ],
            'languagebeheer' => [
                // to anyone.
                ['actions' => '*', 'allow' => '+language.manage']
            ],
        ]
    ],
    'translatorSettings' => [
        'defaultLanguage' => 'ned',
        'defaultIndexes' => [
            'translator.head.title',
            'translator.new.index',
            'translator.edit.index',
            'translator.generate.languages.files',
            'search.placeholder',
            'search.button.text',
            'no.translation.indexes.found',
            'translator.table.index.title',
            'translator.table.creation.date.title',
            'translator.head.title.add.index',
            'translator.head.title.edit.index',
            'manage.translations',
            'translator.head.title.manage.translations.index',
            'translator.head.title.generate.files',
            'button.generate',
            'no.translation.files.found',
            'language.file.generated',
            'language.file.not.generated',
            'language.not.found',
            'language.add',
            'language.edit',
            'languages.new.title',
            'languages.head.title',
            'manage.languages',
            'manage.language.files',
            'no.languages.found',
            'language.table.shortname.title',
            'language.table.name.title',
            'language.table.date.created.title',
            'translation.index.added',
            'translation.index.changed',
            'translation.index.removed',
            'translation.index.not.found',
            'translations.added',
            'translations.added.error',
            'translation.edit',
            'id.not.found',
            'home.text',
            'save',
            'cancel',
        ],
        'defaultTranslations' => [
            'ned' => [
                'translator.head.title' => 'Vertalingen',
                'translator.new.index' => 'Nieuwe index',
                'translator.edit.index' => 'Wijzig index',
                'translator.generate.languages.files' => 'Genereer vertalings bestanden',
                'search.placeholder' => 'Zoeken',
                'search.button.text' => 'Zoeken',
                'no.translation.indexes.found' => 'Geen indexes gevonden!',
                'translator.table.index.title' => 'Naam',
                'translator.table.creation.date.title' => 'Aanmaak datum',
                'translator.head.title.add.index' => 'Voeg nieuwe index toe',
                'translator.head.title.edit.index' => 'Wijzig index',
                'manage.translations' => 'Beheer vertalingen',
                'translator.head.title.manage.translations.index' => 'Beheer vertalingen voor indexes',
                'translator.head.title.generate.files' => 'Genereer vertalings bestanden',
                'button.generate' => 'Genereer',
                'no.translation.files.found' => 'Geen talen voor bestanden gevonden!',
                'language.file.generated' => 'vertaal bestand gegenereert',
                'language.file.not.generated' => 'vertaal bestand niet gegenereerd',
                'language.not.found' => 'Taal niet gevonden',
                'language.add' => 'Voeg nieuwe taal toe',
                'language.edit' => 'Wijzig taal',
                'languages.new.title' => 'Nieuwe taal',
                'languages.head.title' => 'Beheer talen',
                'manage.languages' => 'Beheer talen',
                'manage.language.files' => 'Beheer taal bestanden',
                'no.languages.found' => 'Geen talen gevonden!',
                'language.table.shortname.title' => 'Korte naam',
                'language.table.name.title' => 'Naam',
                'language.table.date.created.title' => 'Datum aangemaakt',
                'translation.index.added' => 'Vertalings index toegevoegd',
                'translation.index.changed' => 'Vertalings index gewijzigd',
                'translation.index.removed' => 'Vertalings index verwijderd',
                'translation.index.not.found' => 'Vertalings index niet gevonden',
                'translations.added' => 'Vertaling(en) toegevoegd',
                'translations.added.error' => 'Iets ging verkeerd. Probeer het opnieuw',
                'translation.edit' => 'Wijzig vertalingen',
                'id.not.found' => 'id niet gevonden',
                'home.text' => 'home',
                'save' => 'Opslaan',
                'cancel' => 'Annuleren',
            ],
        ],
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
