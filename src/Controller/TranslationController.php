<?php

namespace Translator\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class TranslationController extends AbstractActionController {

    protected $vhm;
    protected $translationIndexService;
    protected $languageService;
    protected $translationService;
    protected $translator;

    public function __construct($vhm, $translationIndexService, $languageService, $translationService, $translator) {
        $this->vhm = $vhm;
        $this->translationIndexService = $translationIndexService;
        $this->languageService = $languageService;
        $this->translationService = $translationService;
        $this->translator = $translator;
    }

    public function indexAction() {
        $translationIndexes = $this->translationIndexService->getTranslationIndexes();
        $searchString = '';
        if ($this->getRequest()->isPost()) {
            $searchString = $this->getRequest()->getPost('search');
            $translationIndexes = $this->translationIndexService->searchTranslationIndexes($searchString);
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
                $this->translationIndexService->saveTranslationIndex($translationIndex, $this->currentUser());
                $this->flashMessenger()->addSuccessMessage($this->translator->translate('translation.index.added'));
                return $this->redirect()->toRoute('beheer/translations');
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
            $this->flashMessenger()->addErrorMessage($this->translator->translate('id.not.found'));
            return $this->redirect()->toRoute('beheer/translations');
        }
        $translationIndex = $this->translationIndexService->getTranslationIndex($id);
        if (empty($translationIndex)) {
            $this->flashMessenger()->addErrorMessage($this->translator->translate('translation.index.not.found'));
            return $this->redirect()->toRoute('beheer/translations');
        }
        $form = $this->translationIndexService->createForm($translationIndex);

        if ($this->getRequest()->isPost()) {
            $form->setData($this->getRequest()->getPost());
            if ($form->isValid()) {
                //Save Customer
                $this->translationIndexService->updateTranslationIndex($translationIndex, $this->currentUser());
                $this->flashMessenger()->addSuccessMessage($this->translator->translate('translation.index.changed'));
                return $this->redirect()->toRoute('beheer/translations');
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
            $this->flashMessenger()->addErrorMessage($this->translator->translate('id.not.found'));
            return $this->redirect()->toRoute('beheer/translations');
        }
        $translationIndex = $this->translationIndexService->getTranslationIndex($id);
        if (empty($translationIndex)) {
            $this->flashMessenger()->addErrorMessage($this->translator->translate('translation.index.not.found'));
            return $this->redirect()->toRoute('beheer/translations');
        }
        $this->translationIndexService->deleteTranslationIndex($translationIndex);
        $this->flashMessenger()->addSuccessMessage($this->translator->translate('translation.index.removed'));
        return $this->redirect()->toRoute('beheer/translations');
    }

    public function translationAction() {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (empty($id)) {
            $this->flashMessenger()->addErrorMessage($this->translator->translate('id.not.found'));
            return $this->redirect()->toRoute('beheer/translations');
        }
        $translationIndex = $this->translationIndexService->getTranslationIndex($id);
        if (empty($translationIndex)) {
            $this->flashMessenger()->addErrorMessage($this->translator->translate('translation.index.not.found'));
            return $this->redirect()->toRoute('beheer/translations');
        }
        //Check if post
        if ($this->getRequest()->isPost()) {
            $result = $this->translationService->saveTranslations($this->getRequest()->getPost(), $translationIndex, $this->currentUser());
            if ($result) {
                $this->flashMessenger()->addSuccessMessage($this->translator->translate('translations.added'));
            } else {
                $this->flashMessenger()->addErrorMessage($this->translator->translate('translations.added.error'));
            }
            return $this->redirect()->toRoute('beheer/translations');
        }
        //Get translations for translationIndex
        $translations = $this->translationService->getTranslationsByIndexId($translationIndex->getId());
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

}
