<?php

namespace Translator\Service;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Session\Container;

/*
 * Entities
 */
use Translator\Entities\Translation;

class translatorService implements translatorServiceInterface {

    protected $entityManager;
    protected $languageService;
    protected $translationIndexService;
    protected $config;

    /**
     * Constructor.
     */
    public function __construct($entityManager, $languageService, $translationIndexService, $config) {
        $this->entityManager = $entityManager;
        $this->languageService = $languageService;
        $this->translationIndexService = $translationIndexService;
        $this->config = $config;
    }

    /**
     *
     * Generate language file
     *
     * @param       language $language object
     * @return      void
     *
     */
    public function generateLanguageFile($translations, $shortName) {

        //Set data for languages file
        $languageFileData = '';
        foreach ($translations AS $translation) {
            $languageFileData .= "'" . $translation->getTranslationIndex()->getIndex() . "'  => '" . $translation->getTranslation() . "'," . PHP_EOL;
        }
        //Create data for file
        $fileContent = '<?php' . PHP_EOL;
        $fileContent .= 'return [' . PHP_EOL;
        $fileContent .= '"translations" =>  [' . PHP_EOL;
        $fileContent .= $languageFileData;
        $fileContent .= '],' . PHP_EOL;
        $fileContent .= '];';

        //Try to save data to file
        try {
            //Set data to file
            file_put_contents(__DIR__ . '\..\..\locales\/' . strtolower($shortName) . '.php', $fileContent);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     *
     * Set default languages
     *
     * @return      void
     *
     */
    public function defaultLanguages() {

        //Save default languages
        $language = $this->languageService->newLanguage();
        $language->setName('Dutch');
        $language->setShortName('NED');
        $this->languageService->saveLanguage($language, NULL);

        $language = $this->languageService->getLanguageByShortName('NED');


        //Save default indexes
        $defaultIndexes = $this->config['translatorSettings']['defaultIndexes'];
        foreach ($defaultIndexes AS $index) {
            $translationIndex = $this->translationIndexService->newTranslationIndex();
            $translationIndex->setIndex($index);
            $translationIndex = $this->translationIndexService->saveTranslationIndex($translationIndex, NULL);
        }

        $defaultTranslations = $this->config['translatorSettings']['defaultTranslations']['ned'];
        foreach ($defaultTranslations AS $index => $translation) {
            $translationIndex = $this->translationIndexService->getTranslationIndexByIndex($index);
            $translationObject = $this->newTranslation();
            $translationObject->setTranslation($translation);
            $translationObject->setTranslationIndex($translationIndex);
            $translationObject->setLanguage($language);
            $this->saveTranslation($translationObject, NULL);
        }
        $translations = $this->getTranslationsByLanguageId($language->getId());
        $this->generateLanguageFile($translations, $language->getShortName());
    }

    /**
     *
     * Set language in session
     *
     * @return      void
     *
     */
    public function setLanguageInSession($language) {
        $sessionContainer = new Container('Translator');
        $sessionContainer->language = $language;
        
        return true;
    }

}
