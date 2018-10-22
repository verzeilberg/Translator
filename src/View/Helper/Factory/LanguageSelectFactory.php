<?php
namespace Translator\View\Helper\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Translator\View\Helper\LanguageSelect;
use Translator\Service\languageService;

/**
 * This is the factory for Menu view helper. Its purpose is to instantiate the
 * helper and init menu items.
 */
class LanguageSelectFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {   
        $viewHelperManager = $container->get('ViewHelperManager');
        $urlHelper = $viewHelperManager->get('url');
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $languageService = new languageService($entityManager);
        // Instantiate the helper.
        return new LanguageSelect($languageService, $urlHelper);
    }
}

