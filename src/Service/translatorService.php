<?php

namespace Translator\Service;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Mail;
use Zend\Mime\Part as MimePart;
use Zend\Mime\Message as MimeMessage;

/*
 * Entities
 */
use Translator\Entities\Translation;

class translatorService implements translatorServiceInterface {

    protected $entityManager;
    protected $languageService;

    /**
     * Constructor.
     */
    public function __construct($entityManager, $languageService) {
        $this->entityManager = $entityManager;
        $this->languageService = $languageService;
    }

    /**
     *
     * Get array of translations
     *
     * @return      array
     *
     */
    public function getTranslations() {

        $translators = $this->entityManager->getRepository(Translation::class)
                ->findBy([], ['dateCreated' => 'DESC']);

        return $translators;
    }

    /**
     *
     * Get translation object based on id
     *
     * @param       id  $id The id to fetch the translation from the database
     * @return      object
     *
     */
    public function getTranslation($id) {
        $translation = $this->entityManager->getRepository(Translation::class)
                ->findOneBy(['id' => $id], []);

        return $translation;
    }

    public function getTranslationsByIndexId($id) {
        $qb = $this->entityManager->getRepository(Translation::class)->createQueryBuilder('t');
        $qb->where('t.translationIndex = ' . $id);
        $query = $qb->getQuery();
        $result = $query->getResult();
        $translations = [];
        foreach ($result AS $item) {
            $translations[$item->getLanguage()->getId()]['id'] = $item->getId();
            $translations[$item->getLanguage()->getId()]['translation'] = $item->getTranslation();
            $translations[$item->getLanguage()->getId()]['translationIndex'] = $item->getTranslationIndex()->getId();
        }
        return $translations;
    }

    /**
     *
     * Create translations objects and save to database
     *
     * @param       translations  $translations array of translations
     * @param       translationIndex $translationIndex translation index the translation must be linked to
     * @return      void
     *
     */
    public function saveTranslations($translations, $translationIndex, $user) {
        if (count($translations) > 0) {
            $languages = $this->languageService->getLanguages();

            foreach ($languages AS $language) {
                if (array_key_exists('languages_' . $language->getId(), $translations)) {

                    if (array_key_exists('translation_' . $language->getId(), $translations)) {
                        $translationId = $translations['translation_' . $language->getId()];
                        $translation = $this->getTranslation($translationId);
                        $value = $translations['languages_' . $language->getId()];
                        $translation->setTranslation($value);
                        $this->updateTranslation($translation, $user);
                    } else {
                        $translation = $this->newTranslation();
                        $value = $translations['languages_' . $language->getId()];
                        $translation->setTranslation($value);
                        $translation->setTranslationIndex($translationIndex);
                        $translation->setLanguage($language);
                        $this->saveTranslation($translation, $user);
                    }
                }
            }
            return true;
        } else {
            return false;
        }
    }

    /**
     *
     * Generate language file
     *
     * @param       language $language object
     * @return      void
     *
     */
    public function generateLanguageFile($language) {
        //Get translations
        $translations = $language->getTranslations();
        //Set data for languages file
        $languageFileData = '';
        foreach ($translations AS $translation) {
            $languageFileData .= "'" . $translation->getTranslationIndex()->getIndex() . "'  => '" . $translation->getTranslation() . "'," . PHP_EOL;
        }
        //Create data for file
        $fileContent = '<?php' . PHP_EOL;
        $fileContent .= 'return [' . PHP_EOL;
        $fileContent .= $languageFileData;
        $fileContent .= '];';
        
        //Try to save data to file
        try {
            //Set data to file
            file_put_contents(__DIR__ . '/..//../locales/' . strtolower($language->getShortName()) . '.php', $fileContent);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     *
     * Create form of an object
     *
     * @param       translation $translation object
     * @return      form
     *
     */
    public function createForm($translation) {
        $builder = new AnnotationBuilder($this->entityManager);
        $form = $builder->createForm($translation);
        $form->setHydrator(new DoctrineHydrator($this->entityManager, 'Translator\Entities\Translator'));
        $form->bind($translation);

        return $form;
    }

    /**
     *
     * Create a new translation object
     * @return      object
     *
     */
    public function newTranslation() {
        $translation = new Translation();
        return $translation;
    }

    /**
     *
     * Save a translation to database
     * @param       translation $translation object
     * @param       user $user object
     * @return      void
     *
     */
    public function saveTranslation($translation, $user) {
        $translation->setDateCreated(new \DateTime());
        $translation->setCreatedBy($user);
        $this->storeTranslation($translation);
    }

    /**
     *
     * Update a translation to database
     * @param       translation $translation object
     * @param       user $user object
     * @return      void
     *
     */
    public function updateTranslation($translation, $user) {
        $translation->setDateChanged(new \DateTime());
        $translation->setChangedBy($user);
        $this->storeTranslation($translation);
    }

    /**
     *
     * Save a translation to database
     * @param       contact $translation object
     * @return      void
     *
     */
    public function storeTranslation($translation) {
        $this->entityManager->persist($translation);
        $this->entityManager->flush();
    }

    /**
     *
     * Delete a translation object from database
     * @param       translation $translation object
     * @return      void
     *
     */
    public function deleteTranslation($translation) {
        $this->entityManager->remove($translation);
        $this->entityManager->flush();
    }

}
