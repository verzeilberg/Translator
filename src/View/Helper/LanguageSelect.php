<?php

namespace Translator\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Zend\Session\Container;

// This view helper class translate text
class LanguageSelect extends AbstractHelper {

    protected $languageService;

    /**
     * Url view helper.
     * @var Zend\View\Helper\Url
     */
    private $urlHelper;

    function __construct($languageService, $urlHelper) {
        $this->languageService = $languageService;
        $this->urlHelper = $urlHelper;
    }

    public function generateLanguageSelect() {
        $sessionContainer = new Container('Translator');
        $selectedLanguage = $sessionContainer->language;
        

        $url = $this->urlHelper;

        $query = $this->languageService->getLanguages();
        $languages = $query->getResult();
        echo '<div class="col-md-1 mt-3">';
        echo '<form method="post" action="' . $url('language', ['action' => 'language-select']) . '" name="selectLanguages">';
        echo '<input type="hidden" name="redirectURL" value="'.$url().'" />';
        echo '<select name="selectLanguage" class="form-control" onChange="$(this).parent(\'form\').submit()">';
        foreach ($languages AS $language) {
            echo '<option value="' . $language->getId() . '" '.(is_object($selectedLanguage) && $selectedLanguage->getId() == $language->getId()? 'selected':'').'>' . strtolower($language->getShortName()) . '</option>';
        }
        echo '</select>';
        echo '</form>';
        echo '</div>';
    }

}
