<?php

namespace Translator\Service;

use Zend\ServiceManager\ServiceLocatorInterface;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use DoctrineORMModule\Form\Annotation\AnnotationBuilder;

/*
 * Entities
 */
use Translator\Entities\Language;

class languageService implements languageServiceInterface {

    /**
     * Constructor.
     */
    public function __construct($entityManager) {
        $this->entityManager = $entityManager;
    }

    /**
     *
     * Get array of languagess
     *
     * @return      array
     *
     */
    public function getLanguages() {

        $languagess = $this->entityManager->getRepository(Language::class)
                ->findBy([], ['name' => 'ASC']);

        return $languagess;
    }

    /**
     *
     * Get languages object by on id
     *
     * @param       id  $id The id to fetch the languages from the database
     * @return      object
     *
     */
    public function getLanguage($id) {
        $languages = $this->entityManager->getRepository(Language::class)
                ->findOneBy(['id' => $id], []);

        return $languages;
    }
    
        /**
     *
     * Get languages object by on short name
     *
     * @param       shortName  $shortName The short name to fetch the languages from the database
     * @return      object
     *
     */
    public function getLanguageByShortName($shortName) {
        $language = $this->entityManager->getRepository(Language::class)
                ->findOneBy(['shortName' => $shortName], []);

        return $language;
    }
    
        /**
     *
     * Get array of languages
     * @var $searchString string to search for
     *
     * @return      array
     *
     */
    public function searchLanguages($searchString) {
        $qb = $this->entityManager->getRepository(Language::class)->createQueryBuilder('l');
        $orX = $qb->expr()->orX();
        $orX->add($qb->expr()->like('l.name', $qb->expr()->literal("%$searchString%")));
        $orX->add($qb->expr()->like('l.shortName', $qb->expr()->literal("%$searchString%")));
        $qb->where($orX);
        $query = $qb->getQuery();
        $result = $query->getResult();
        return $result;
    }

    /**
     *
     * Create form of an object
     *
     * @param       blog $event $languages
     * @return      form
     *
     */
    public function createForm($languages) {
        $builder = new AnnotationBuilder($this->entityManager);
        $form = $builder->createForm($languages);
        $form->setHydrator(new DoctrineHydrator($this->entityManager, 'Customers\Entity\Language'));
        $form->bind($languages);

        return $form;
    }

    /**
     *
     * Create a new languages object
     * @return      object
     *
     */
    public function newLanguage() {
        $language = new Language();
        return $language;
    }

    /**
     *
     * Save a languages to database
     * @param       language $language object
     * @param       user $user object
     * @return      void
     *
     */
    public function saveLanguage($language, $user) {
        $language->setDateCreated(new \DateTime());
        $language->setCreatedBy($user);
        $this->storeLanguage($language);
    }

    /**
     *
     * Update a languages to database
     * @param       language $language object
     * @param       user $user object
     * @return      void
     *
     */
    public function updateLanguage($language, $user) {
        $language->setDateChanged(new \DateTime());
        $language->setChangedBy($user);
        $this->storeLanguage($language);
    }

    /**
     *
     * Save a languages to database
     * @param       languages $languages object
     * @return      void
     *
     */
    public function storeLanguage($language) {
        $this->entityManager->persist($language);
        $this->entityManager->flush();
    }

    /**
     *
     * Delete a languages from database
     * @param       languages $languages object
     * @return      void
     *
     */
    public function deleteLanguage($language) {
        $this->entityManager->remove($language);
        $this->entityManager->flush();
    }

}
