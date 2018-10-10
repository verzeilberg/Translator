<?php

namespace Translator\Entities;

use Doctrine\ORM\Mapping as ORM;
use Zend\Form\Annotation;
use Doctrine\Common\Collections\ArrayCollection;
use Application\Model\UnityOfWork;

/**
 * This class represents a translator item.
 * @ORM\Entity()
 * @ORM\Table(name="translation_indexes")
 */
class TranslationIndex extends UnityOfWork {

    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="integer", length=11)
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(name="index_text", type="string", length=255, nullable=false)
     * @Annotation\Options({
     * "label": "Index",
     * "label_attributes": {"class": "col-sm-4 col-md-4 col-lg-4 col-form-label"}
     * })
     * @Annotation\Attributes({"class":"form-control", "placeholder":"Index"})
     */
    protected $index;

     /**
     * One Translation index has Many translations.
     * @ORM\OneToMany(targetEntity="Translation", mappedBy="translationIndex", orphanRemoval=true)
     */
    private $translations;

    public function __construct() {
        $this->translations = new ArrayCollection();
    }

    function getId() {
        return $this->id;
    }

    function getIndex() {
        return $this->index;
    }

    function getTranslations() {
        return $this->translations;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setIndex($index) {
        $this->index = $index;
    }

    function setTranslations($translations) {
        $this->translations = $translations;
    }


    
}
