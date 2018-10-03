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
     * @ORM\Column(name="index", type="string", length=255, nullable=false)
     * @Annotation\Options({
     * "label": "Index",
     * "label_attributes": {"class": "col-sm-4 col-md-4 col-lg-4 col-form-label"}
     * })
     * @Annotation\Attributes({"class":"form-control", "placeholder":"Index"})
     */
    protected $index;

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
     * Many translations have One language.
     * @ORM\ManyToOne(targetEntity="Language", inversedBy="translations")
     * @ORM\JoinColumn(name="language_id", referencedColumnName="id")
     */
    private $language;

    function getId() {
        return $this->id;
    }

    function getIndex() {
        return $this->index;
    }

    function getTranslation() {
        return $this->translation;
    }

    function getLanguage() {
        return $this->language;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setIndex($index) {
        $this->index = $index;
    }

    function setTranslation($translation) {
        $this->translation = $translation;
    }

    function setLanguage($language) {
        $this->language = $language;
    }

}
