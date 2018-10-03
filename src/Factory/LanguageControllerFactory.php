<?php

namespace Translator\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Translator\Controller\LanguageController;
use UploadFiles\Service\uploadfilesService;
use Translator\Service\languageService;
use UploadImages\Service\cropImageService;
use UploadImages\Service\imageService;

/**
 * This is the factory for AuthController. Its purpose is to instantiate the controller
 * and inject dependencies into its constructor.
 */
class LanguageControllerFactory implements FactoryInterface {

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {

        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $vhm = $container->get('ViewHelperManager');
        $config = $container->get('config');
        $ls = new languageService($entityManager);
        $uploadfilesService = new uploadfilesService($config, $entityManager);
        $cropImageService = new cropImageService($entityManager, $config);
        $imageService = new imageService($entityManager, $config);
        return new LanguageController($vhm, $entityManager, $ls, $uploadfilesService, $cropImageService, $imageService);
    }

}
