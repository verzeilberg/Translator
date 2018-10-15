<?php

namespace Translator\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class TranslatorController extends AbstractActionController {

    protected $vhm;
    protected $translatorService;
    protected $languageService;
    protected $translator;

    public function __construct($vhm, $translatorService, $languageService, $translator) {
        $this->vhm = $vhm;
        $this->translatorService = $translatorService;
        $this->languageService = $languageService;
        $this->translator = $translator;
    }

    public function indexAction() {
         //Get languages
        $page = $this->params()->fromQuery('page', 1);
        $query = $this->languageService->getLanguages();
        $languages = $this->languageService->getLanguagesForPagination($query, $page, 10);

        return new ViewModel(
                array(
            'languages' => $languages
                )
        );
    }
    
    public function generateFileAction() {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (empty($id)) {
            $this->flashMessenger()->addErrorMessage($this->translator->translate('id.not.found'));
            return $this->redirect()->toRoute('beheer/translators');
        }
        $language = $this->languageService->getLanguage($id);
        if (empty($language)) {
            $this->flashMessenger()->addErrorMessage($this->translator->translate('language.not.found'));
            return $this->redirect()->toRoute('beheer/translators');
        }
        $result = $this->translatorService->generateLanguageFile($language->getTranslations(), $language->getShortName());
        if($result){
            $language->setGeneratedFileDate(new \DateTime());
            $this->languageService->storeLanguage($language);
            $this->flashMessenger()->addSuccessMessage($language->getName() . ' ' . $this->translator->translate('language.file.generated'));
        } else {
            $this->flashMessenger()->addErrorMessage($language->getName() . ' ' . $this->translator->translate('language.file.not.generated'));
        }
        return $this->redirect()->toRoute('beheer/translators', array('action' => 'index'));
    }

}
