<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Translator;

use Zend\EventManager\EventInterface as Event;
use Translator\Service\languageService;
use Translator\Service\translationIndexService;
use Translator\Service\translatorService;
use Translator\Service\translationService;

class Module
{

    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    /**
     * @param Event $e
     */
    public function onBootstrap(Event $e)
    {
        // This method is called once the MVC bootstrapping is complete
        $application = $e->getApplication();
        $services    = $application->getServiceManager();
        $entityManager = $services->get('doctrine.entitymanager.orm_default');
        $config = $services->get('config');

        //Check if there are languages installed if not make one
        $languageService = new languageService($entityManager);
        $query = $languageService->getLanguages();
        $languages = $languageService->getLanguagesForPagination($query, 1, 10);
        if(count($languages) == 0) {
            $translationIndexService = new translationIndexService($entityManager);
            $translationService = new translationService($entityManager, $languageService, $translationIndexService);
            $translatorService = new translatorService($entityManager, $languageService, $translationIndexService, $translationService, $config);
            $translatorService->defaultLanguages();
        }
    }
}
