<?php

namespace Translator\Service;

interface translatorServiceInterface {

    /**
     *
     * Get array of translators
     *
     * @return      array
     *
     */
    public function getTranslators();
    
    public function getTranslatorFormById($id);
    
    public function deleteTranslatorForm($translatorForm);

    /**
     *
     * Send mail
     * 
     * @param       translator $translator object
     * @return      void
     *
     */
    public function sendMail($translator);

    /**
     *
     * Get base url
     * 
     * @return      string
     *
     */
    public function getBaseUrl();
}
