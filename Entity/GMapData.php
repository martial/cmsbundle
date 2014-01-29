<?php

namespace scrclub\CMSBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;


use Gedmo\Translatable\Translatable;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Category
 *
 * @ORM\Table()
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="scrclub\CMSBundle\Entity\CategoryRepository")
 * @Gedmo\TranslationEntity(class="scrclub\CMSBundle\Entity\Translation\CategoryTranslation")
 */

class GMapData implements Translatable
{

    public function __construct()
    {


    }

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     *
     */
    private $formatedAdress;



    /**
     * @var string
     *
     * @ORM\Column(name="state", type="string", length=255, nullable=true)
     *
     */
    private $state;

    /**
     * @var string
     *
     * @ORM\Column(name="stateshrt", type="string", length=255, nullable=true)
     *
     */
    private $stateShort;

    /**
     * @var string
     *
     * @ORM\Column(name="region", type="string", length=255, nullable=true)
     *
     */
    private $region;

    /**
     * @var string
     *
     * @ORM\Column(name="regionshrt", type="string", length=255, nullable=true)
     *
     */
    private $regionShort;


    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", length=255, nullable=true)
     *
     */
    private $city;

    /**
     * @var string
     *
     * @ORM\Column(name="cityshort", type="string", length=255, nullable=true)
     *
     */
    private $cityShort;

    /**
     * @var string
     *
     * @ORM\Column(name="ctry", type="string", length=255, nullable=true)
     *
     */
    private $country;

    /**
     * @var string
     *
     * @ORM\Column(name="ctryshort", type="string", length=255, nullable=true)
     *
     */
    private $countryShort;

    /**
     * @ORM\OneToOne(targetEntity="Node", mappedBy="gMapData")
     */
    private $node;

    /**
     * @param string $city
     */
    public function setCity($city) {
        $this->city = $city;
    }

    /**
     * @return string
     */
    public function getCity() {
        return $this->city;
    }

    /**
     * @param string $cityShort
     */
    public function setCityShort($cityShort) {
        $this->cityShort = $cityShort;
    }

    /**
     * @return string
     */
    public function getCityShort() {
        return $this->cityShort;
    }

    /**
     * @param string $country
     */
    public function setCountry($country) {
        $this->country = $country;
    }

    /**
     * @return string
     */
    public function getCountry() {
        return $this->country;
    }

    /**
     * @param string $countryShort
     */
    public function setCountryShort($countryShort) {
        $this->countryShort = $countryShort;
    }

    /**
     * @return string
     */
    public function getCountryShort() {
        return $this->countryShort;
    }

    /**
     * @param string $formatedAdress
     */
    public function setFormatedAdress($formatedAdress) {
        $this->formatedAdress = $formatedAdress;
    }

    /**
     * @return string
     */
    public function getFormatedAdress() {
        return $this->formatedAdress;
    }

    /**
     * @param string $region
     */
    public function setRegion($region) {
        $this->region = $region;
    }

    /**
     * @return string
     */
    public function getRegion() {
        return $this->region;
    }

    /**
     * @param string $regionShort
     */
    public function setRegionShort($regionShort) {
        $this->regionShort = $regionShort;
    }

    /**
     * @return string
     */
    public function getRegionShort() {
        return $this->regionShort;
    }

    /**
     * @param string $state
     */
    public function setState($state) {
        $this->state = $state;
    }

    /**
     * @return string
     */
    public function getState() {
        return $this->state;
    }

    /**
     * @param string $stateShort
     */
    public function setStateShort($stateShort) {
        $this->stateShort = $stateShort;
    }

    /**
     * @return string
     */
    public function getStateShort() {
        return $this->stateShort;
    }

    /**
     * @param int $id
     */
    public function setId($id) {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @param mixed $node
     */
    public function setNode($node) {
        $this->node = $node;
    }

    /**
     * @return mixed
     */
    public function getNode() {
        return $this->node;
    }




    public function __toString()
    {
        return "gmapdata";
    }


}
