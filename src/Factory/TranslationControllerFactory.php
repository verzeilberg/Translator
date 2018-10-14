<?php

namespace Translator\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Translator\Controller\TranslationController;
use Translator\Service\translationIndexService;
use Translator\Service\languageService;
use Translator\Service\translationService;
/**
 * This is the factory for AuthController. Its purpose is to instantiate the controller
 * and inject dependencies into its constructor.
 */
class TranslationControllerFactory implements FactoryInterface {

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $vhm = $container->get('ViewHelperManager');
        $translator = $vhm->get('translator');
        $languageService = new languageService($entityManager);
        $translationIndexService = new translationIndexService($entityManager);
        $translationService = new translationService($entityManager, $languageService, $translationIndexService);
        return new TranslationController(
                $vhm, 
                $translationIndexService, 
                $languageService,
                $translationService,
                $translator
                );
    }

}
