<?php

namespace Translator\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class TranslatorController extends AbstractActionController {

    protected $vhm;
    protected $em;
    protected $translatorService;
    protected $translationIndexService;
    protected $languageService;

    public function __construct($vhm, $em, $translatorService, $translationIndexService, $languageService) {
        $this->vhm = $vhm;
        $this->em = $em;
        $this->translatorService = $translatorService;
        $this->translationIndexService = $translationIndexService;
        $this->languageService = $languageService;
    }

    public function indexAction() {
        $translationIndexes = $this->translationIndexService->getTranslationIndexes();

        $searchString = '';
        if ($this->getRequest()->isPost()) {
            $searchString = $this->getRequest()->getPost('search');
            $customers = $this->cs->searchCustomers($searchString);
        }

        return new ViewModel(
                array(
            'translationIndexes' => $translationIndexes,
            'searchString' => $searchString
                )
        );
    }

    public function addAction() {
        $translationIndex = $this->translationIndexService->newTranslationIndex();
        $form = $this->translationIndexService->createForm($translationIndex);

        if ($this->getRequest()->isPost()) {
            $form->setData($this->getRequest()->getPost());

            if ($form->isValid()) {
                //Save Customer
                $this->translationIndexService->saveTranslation($translationIndex, $this->currentUser());

                return $this->redirect()->toRoute('beheer/translators');
            }
        }

        return new ViewModel(
                array(
            'form' => $form
                )
        );
    }

    public function editAction() {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (empty($id)) {
            return $this->redirect()->toRoute('beheer/translators');
        }
        $translationIndex = $this->translationIndexService->getTranslationIndex($id);
        if (empty($translationIndex)) {
            return $this->redirect()->toRoute('beheer/translators');
        }
        $form = $this->translationIndexService->createForm($translationIndex);

        if ($this->getRequest()->isPost()) {
            $form->setData($this->getRequest()->getPost());

            if ($form->isValid()) {
                //Save Customer
                $this->translationIndexService->updateTranslationIndex($translationIndex, $this->currentUser());

                return $this->redirect()->toRoute('beheer/translators');
            }
        }

        return new ViewModel(
                array(
            'form' => $form
                )
        );
    }
    
    public function deleteAction() { 
        $id = (int) $this->params()->fromRoute('id', 0);
        if (empty($id)) {
            return $this->redirect()->toRoute('beheer/translators');
        }
        $translationIndex = $this->translationIndexService->getTranslationIndex($id);
        if (empty($translationIndex)) {
            return $this->redirect()->toRoute('beheer/translators');
        }
        $this->translationIndexService->deleteTranslationIndex($translationIndex);
        $this->flashMessenger()->addSuccessMessage('Translation index removed');
        return $this->redirect()->toRoute('beheer/translators');
    }

    public function translationsAction() {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (empty($id)) {
            return $this->redirect()->toRoute('beheer/translators');
        }
        $translationIndex = $this->translationIndexService->getTranslationIndex($id);
        if (empty($translationIndex)) {
            return $this->redirect()->toRoute('beheer/translators');
        }
        //Check if post
        if ($this->getRequest()->isPost()) {
            $result = $this->translatorService->saveTranslations($this->getRequest()->getPost(), $translationIndex, $this->currentUser());
        }
        //Get translations for translationIndex
        $translations = $this->translatorService->getTranslationsByIndexId($translationIndex->getId());
        //Get languages
        $languages = $this->languageService->getLanguages();
        return new ViewModel(
                array(
            'translationIndex' => $translationIndex,
            'languages' => $languages,
            'translations' => $translations
                )
        );
    }

    public function generateFilesAction() {
        //Get languages
        $languages = $this->languageService->getLanguages();


        return new ViewModel(
                array(
            'languages' => $languages
                )
        );
    }
    
    public function generateFileAction() {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (empty($id)) {
            return $this->redirect()->toRoute('beheer/translators');
        }
        $language = $this->languageService->getLanguage($id);
        if (empty($language)) {
            return $this->redirect()->toRoute('beheer/translators');
        }
        $result = $this->translatorService->generateLanguageFile($language);
        if($result){
            $language->setGeneratedFileDate(new \DateTime());
            $this->languageService->storeLanguage($language);
            $this->flashMessenger()->addSuccessMessage($language->getName() . ' Language file generated');
        } else {
            $this->flashMessenger()->addErrorMessage($language->getName() . ' Language file not generate');
        }
        return $this->redirect()->toRoute('beheer/translators', array('action' => 'generate-files'));
    }

}
