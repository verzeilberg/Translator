<?php

namespace Translator\View\Helper;

use Zend\View\Helper\AbstractHelper;

// This view helper class translate text
class Translate extends AbstractHelper {

    public function translate($translation) {

        $translations = [
            'bla' => 'blub',
            'blow' => 'blop',
            'no.customers.found' => 'No customers found!'
        ];

        if (array_key_exists($translation, $translations)) {
            $result = $translations[$translation];
        } else {
            $result = $translation;
        }

        return $result;
    }

}
