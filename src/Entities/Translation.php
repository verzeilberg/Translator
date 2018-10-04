<?php

namespace Translator\Entities;

use Doctrine\ORM\Mapping as ORM;
use Zend\Form\Annotation;
use Doctrine\Common\Collections\ArrayCollection;
use Application\Model\UnityOfWork;

/**
 * This class represents a translator item.
 * @ORM\Entity()
 * @ORM\Table(name="translations")
 */
class Translation extends UnityOfWork {

    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="integer", length=11)
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(name="translation", type="string", length=255, nullable=false)
     * @Annotation\Options({
     * "label": "Translation",
     * "label_attributes": {"class": "col-sm-4 col-md-4 col-lg-4 col-form-label"}
     * })
     * @Annotation\Attributes({"class":"form-control", "placeholder":"Translation"})
     */
    protected $translation;

    /**
     * Many Translations have One Translation index.
     * @ORM\ManyToOne(targetEntity="TranslationIndex", inversedBy="translations")
     * @ORM\JoinColumn(name="translation_index_id", referencedColumnName="id")
     */
    private $translationIndex;

    /**
     * Many Translations have One language.
     * @ORM\ManyToOne(targetEntity="Language", inversedBy="translations")
     * @ORM\JoinColumn(name="language_id", referencedColumnName="id")
     */
    private $language;

    function getId() {
        return $this->id;
    }

    function getTranslation() {
        return $this->translation;
    }

    function getTranslationIndex() {
        return $this->translationIndex;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setTranslation($translation) {
        $this->translation = $translation;
    }

    function setTranslationIndex($translationIndex) {
        $this->translationIndex = $translationIndex;
    }

    function getLanguage() {
        return $this->language;
    }

    function setLanguage($language) {
        $this->language = $language;
    }

}
