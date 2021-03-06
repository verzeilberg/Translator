<?php

namespace Translator\Service;

use Zend\ServiceManager\ServiceLocatorInterface;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use DoctrineORMModule\Form\Annotation\AnnotationBuilder;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrineAdapter;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use Zend\Paginator\Paginator;

/*
 * Entities
 */
use Translator\Entities\TranslationIndex;

class translationIndexService implements translationIndexServiceInterface {

    protected $entityManager;

    /**
     * Constructor.
     */
    public function __construct($entityManager) {
        $this->entityManager = $entityManager;
    }

    /**
     *
     * Get array of translations
     *
     * @return      array
     *
     */
    public function getTranslationIndexes() {
        $qb = $this->entityManager->getRepository(TranslationIndex::class)->createQueryBuilder('t');
        $qb->orderBy('t.dateCreated', 'DESC');
        $query = $qb->getQuery();

        return $query;
    }

    /**
     *
     * Get translationIndex object based on id
     *
     * @param       id  $id The id to fetch the translationIndex from the database
     * @return      object
     *
     */
    public function getTranslationIndex($id) {
        $translationIndex = $this->entityManager->getRepository(TranslationIndex::class)
                ->findOneBy(['id' => $id], []);

        return $translationIndex;
    }

    /**
     *
     * Get translationIndex object based on index
     *
     * @param       index  $index The index to fetch the translationIndex from the database
     * @return      object
     *
     */
    public function getTranslationIndexByIndex($index) {
        $translationIndex = $this->entityManager->getRepository(TranslationIndex::class)
                ->findOneBy(['index' => $index], []);

        return $translationIndex;
    }

    /**
     *
     * Get array of translation indexes
     * @var $searchString string to search for
     *
     * @return      array
     *
     */
    public function searchTranslationIndexes($searchString) {
        $qb = $this->entityManager->getRepository(TranslationIndex::class)->createQueryBuilder('t');
        $orX = $qb->expr()->orX();
        $orX->add($qb->expr()->like('t.index', $qb->expr()->literal("%$searchString%")));
        $qb->where($orX);
        $query = $qb->getQuery();
        //$result = $query->getResult();
        return $query;
    }

    /**
     *
     * Get array of translation indexes for pagination
     * @var $query query 
     * @var $currentPage current page 
     * @var $itemsPerPage items on a page 
     *
     * @return      array
     *
     */
    public function getTranslationIndexesForPagination($query, $currentPage = 1, $itemsPerPage = 10) {
        $adapter = new DoctrineAdapter(new ORMPaginator($query, false));
        $paginator = new Paginator($adapter);
        $paginator->setDefaultItemCountPerPage($itemsPerPage);
        $paginator->setCurrentPageNumber($currentPage);
        return $paginator;
    }

    /**
     *
     * Create form of an object
     *
     * @param       translationIndex $translationIndex object
     * @return      form
     *
     */
    public function createForm($translationIndex) {
        $builder = new AnnotationBuilder($this->entityManager);
        $form = $builder->createForm($translationIndex);
        $form->setHydrator(new DoctrineHydrator($this->entityManager, 'Translator\Entities\TranslatorIndex'));
        $form->bind($translationIndex);

        return $form;
    }

    /**
     *
     * Create a new translationIndex object
     * @return      object
     *
     */
    public function newTranslationIndex() {
        $translationIndex = new TranslationIndex();
        return $translationIndex;
    }

    /**
     *
     * Save a translationIndex to database
     * @param       translationIndex $translationIndex object
     * @param       user $user object
     * @return      void
     *
     */
    public function saveTranslationIndex($translationIndex, $user) {
        $translationIndex->setDateCreated(new \DateTime());
        $translationIndex->setCreatedBy($user);
        $this->storeTranslationIndex($translationIndex);
    }

    /**
     *
     * Update a translationIndex to database
     * @param       translationIndex $translationIndex object
     * @param       user $user object
     * @return      void
     *
     */
    public function updateTranslationIndex($translationIndex, $user) {
        $translationIndex->setDateChanged(new \DateTime());
        $translationIndex->setChangedBy($user);
        $this->storeTranslationIndex($translationIndex);
    }

    /**
     *
     * Save a translationIndex to database
     * @param       contact $translationIndex object
     * @return      void
     *
     */
    public function storeTranslationIndex($translationIndex) {
        $this->entityManager->persist($translationIndex);
        $this->entityManager->flush();
    }

    /**
     *
     * Delete a translationIndex object from database
     * @param       translationIndex $translationIndex object
     * @return      void
     *
     */
    public function deleteTranslationIndex($translationIndex) {
        $this->entityManager->remove($translationIndex);
        $this->entityManager->flush();
    }

}
