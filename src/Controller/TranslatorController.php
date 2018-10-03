<?php

namespace Translator\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class TranslatorController extends AbstractActionController {

    protected $vhm;
    protected $em;
    protected $translatorService;

    public function __construct($vhm, $em, $translatorService) {
        $this->vhm = $vhm;
        $this->em = $em;
        $this->translatorService = $translatorService;
    }

    public function indexAction() {
        $translations = $this->translatorService->getTranslations();

        return new ViewModel(
                array(
            'translations' => $translations
                )
        );
    }

}
