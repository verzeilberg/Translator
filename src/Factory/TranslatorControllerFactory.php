<?php

namespace Translator\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Translator\Controller\TranslatorController;
use Translator\Service\translatorService;
use Translator\Service\translationIndexService;z
use Translator\Service\languageService;
/**
 * This is the factory for AuthController. Its purpose is to instantiate the controller
 * and inject dependencies into its constructor.
 */
class TranslatorControllerFactory implements FactoryInterface {

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
        
        $config = $container->get('config');
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $vhm = $container->get('ViewHelperManager');
        $translator = $vhm->get('translator');
        $languageService = new languageService($entityManager);
        $translationIndexService = new translationIndexService($entityManager);
        $translatorService = new translatorService($entityManager, $languageService, $translationIndexService, $config);
        return new TranslatorController(
                $vhm, 
                $translatorService, 
                $languageService,
                $translator
                );
    }

}
