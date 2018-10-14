<?php

namespace Translator\Service;

interface translatorServiceInterface {

    /**
     *
     * Generate language file
     *
     * @param       language $language object
     * @return      void
     *
     */
    public function generateLanguageFile($translations, $shortName);
}
