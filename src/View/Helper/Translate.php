<?php

namespace Translator\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Zend\Session\Container;

// This view helper class translate text
class Translate extends AbstractHelper {

    protected $config;
    protected $translations = null;

    function __construct($config) {
        $this->config = $config;
        $this->setLanguageFile();
    }

    public function translate($translation) {
        if (is_array($this->translations['translations']) && array_key_exists($translation, $this->translations['translations'])) {
            $result = $this->translations['translations'][$translation];
        } else {
            $result = $translation;
        }

        return $result;
    }

    private function setLanguageFile() {
        $sessionContainer = new Container('Translator');
        $language = $sessionContainer->language;

        if (!empty($language)) {
            $shortName = $language->getShortName();
        } else {
            $shortName = $this->config['translatorSettings']['defaultLanguage'];
        }

        if (file_exists(__DIR__ . '\..\..\..\locales\/' . $shortName . '.php')) {
            $this->translations = include_once (__DIR__ . '\..\..\..\locales\/' . $shortName . '.php');
        }
    }

}
