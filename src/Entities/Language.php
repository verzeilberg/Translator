<?php

namespace Customers\Entities;

use Doctrine\ORM\Mapping as ORM;
use Zend\Form\Annotation;
use Doctrine\Common\Collections\ArrayCollection;
use Application\Model\UnityOfWork;

/**
 * This class represents a country item.
 * @ORM\Entity()
 * @ORM\Table(name="countries")
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
     * One country have One Image.
     * @ORM\OneToOne(targetEntity="UploadImages\Entity\Image")
     * @ORM\JoinColumn(name="image_id", referencedColumnName="id", onDelete="SET NULL")
     */
    private $countryImage;

    /**
     * One Language has Many translations.
     * @ORM\OneToMany(targetEntity="Translations", mappedBy="language")
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
        return $this->countryImage;
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

    function setLanguageImage($countryImage) {
        $this->countryImage = $countryImage;
    }
    
    function getCountryImage() {
        return $this->countryImage;
    }

    function getTranslations() {
        return $this->translations;
    }

    function setCountryImage($countryImage) {
        $this->countryImage = $countryImage;
    }

    function setTranslations($translations) {
        $this->translations = $translations;
    }



}
