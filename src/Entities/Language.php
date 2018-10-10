<?php

namespace Translator\Entities;

use Doctrine\ORM\Mapping as ORM;
use Zend\Form\Annotation;
use Doctrine\Common\Collections\ArrayCollection;
use Application\Model\UnityOfWork;

/**
 * This class represents a language item.
 * @ORM\Entity()
 * @ORM\Table(name="languages")
 */
class Language extends UnityOfWork {

    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="integer", length=11)
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     * @Annotation\Options({
     * "label": "Name",
     * "label_attributes": {"class": "col-sm-4 col-md-4 col-lg-4 col-form-label"}
     * })
     * @Annotation\Attributes({"class":"form-control", "placeholder":"Name"})
     */
    protected $name;

    /**
     * @ORM\Column(name="short_name", type="string", length=255, nullable=false)
     * @Annotation\Options({
     * "label": "Short name",
     * "label_attributes": {"class": "col-sm-4 col-md-4 col-lg-4 col-form-label"}
     * })
     * @Annotation\Attributes({"class":"form-control", "placeholder":"Short name"})
     */
    protected $shortName;

    /**
     * @ORM\Column(name="generated_file_date", type="datetime", nullable=true)
     */
    protected $generatedFileDate;

    /**
     * One language have One Image.
     * @ORM\OneToOne(targetEntity="UploadImages\Entity\Image")
     * @ORM\JoinColumn(name="image_id", referencedColumnName="id", onDelete="SET NULL")
     */
    private $languageImage;

    /**
     * One Language has Many translations.
     * @ORM\OneToMany(targetEntity="Translation", mappedBy="language", orphanRemoval=true)
     */
    private $translations;

    public function __construct() {
        $this->translations = new ArrayCollection();
    }

    function getId() {
        return $this->id;
    }

    function getName() {
        return $this->name;
    }

    function getShortName() {
        return $this->shortName;
    }

    function getLanguageImage() {
        return $this->languageImage;
    }

    function getTranslations() {
        return $this->translations;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setName($name) {
        $this->name = $name;
    }

    function setShortName($shortName) {
        $this->shortName = $shortName;
    }

    function setLanguageImage($languageImage) {
        $this->languageImage = $languageImage;
    }

    function setTranslations($translations) {
        $this->translations = $translations;
    }
    
    function getGeneratedFileDate() {
        return $this->generatedFileDate;
    }

    function setGeneratedFileDate($generatedFileDate) {
        $this->generatedFileDate = $generatedFileDate;
    }


}
