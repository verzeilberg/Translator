<?php

namespace Translator\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Translator\Controller\TranslatorController;
use Translator\Service\translatorService;
use Translator\Service\translationIndexService;
use Translator\Service\languageService;
/**
 * This is the factory for AuthController. Its purpose is to instantiate the controller
 * and inject dependencies into its constructor.
 */
class TranslatorControllerFactory implements FactoryInterface {

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
        
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $vhm = $container->get('ViewHelperManager');
        $languageService = new languageService($entityManager);
        $translatorService = new translatorService($entityManager, $languageService);
        $translationIndexService = new translationIndexService($entityManager);
        return new TranslatorController(
                $vhm, 
                $entityManager, 
                $translatorService, 
                $translationIndexService, 
                $languageService
                );
    }

}
