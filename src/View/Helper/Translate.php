<?php

namespace Translator\View\Helper;

use Zend\View\Helper\AbstractHelper;

// This view helper class translate text
class Translate extends AbstractHelper {

    protected $config;
    protected $translations;

    function __construct($config) {
        $this->config = $config;
        $language = $this->config['translatorSettings']['defaultLanguage'];
        $this->translations = include_once (__DIR__ . '\..\..\..\locales\/'.$language.'.php');
    }

    public function translate($translation) {

        if (array_key_exists($translation, $this->translations['translations'])) {
            $result = $this->translations['translations'][$translation];
        } else {
            $result = $translation;
        }

        return $result;
    }

}
