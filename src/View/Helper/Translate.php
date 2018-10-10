<?php

namespace Translator\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Zend\Session\Container;

// This view helper class translate text
class Translate extends AbstractHelper {

    protected $config;
    protected $translations;

    function __construct($config) {
        $this->config = $config;
        $this->setLanguageFile();
    }

    public function translate($translation) {

        if (array_key_exists($translation, $this->translations['translations'])) {
            $result = $this->translations['translations'][$translation];
        } else {
            $result = $translation;
        }

        return $result;
    }

    private function setLanguageFile() {
        $container = new Container('Language');
        $name = $container->offsetGet('name');
        $shortName = $container->offsetGet('shortName');

        if (isset($name)) {
            $language = $shortName;
        } else {
            $language = $this->config['translatorSettings']['defaultLanguage'];
        }
        
        $this->translations = include_once (__DIR__ . '\..\..\..\locales\/' . $language . '.php');
    }

}
