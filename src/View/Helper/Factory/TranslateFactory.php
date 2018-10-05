<?php
namespace Translator\View\Helper\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Translator\View\Helper\Translate;

/**
 * This is the factory for Menu view helper. Its purpose is to instantiate the
 * helper and init menu items.
 */
class TranslateFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {   
        
        $config = $container->get('config');
        // Instantiate the helper.
        return new Translate($config);
    }
}

