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
 * @ORM\Entity(repositoryClass="scrclub\CMSBundle\Entity\ContentTypeRepository")
 * @ORM\InheritanceType("JOINED")
 * @Gedmo\TranslationEntity(class="scrclub\CMSBundle\Entity\Translation\ContentTypeTranslation")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\DiscriminatorMap({"textContentType" = "TextContentType", "booleanContentType"="BooleanContentType" })
 */

class ContentType
{

    public function __construct()
    {

        $this->translations = new ArrayCollection();

    }

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;


    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     *
     */
    protected $name;

    /**
     * @var string
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    protected $description;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255, nullable=true)
     *
     */
    protected $type;



    /**
     * @ORM\OneToMany(
     *  targetEntity="scrclub\CMSBundle\Entity\Translation\ContentTypeTranslation",
     *  mappedBy="object",
     *  cascade={"persist", "remove"}
     * )
     * @Assert\Valid(deep = true)
     */
    protected $translations;


    /**
     * @param string $type
     */
    public function setType($type) {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getType() {
        return $this->type;
    }


    /**
     * @param string $description
     */
    public function setDescription($description) {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getDescription() {
        return $this->description;
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
     * @param string $name
     */
    public function setName($name) {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Set translations
     *
     * @param ArrayCollection $translations
     * @return Node
     */
    public function setTranslations($translations)
    {
        foreach ($translations as $translation) {
            $translation->setObject($this);
        }
        $this->translations = $translations;
        return $this;
    }



    /**
     * Get translations
     *
     * @return ArrayCollection
     */
    public function getTranslations()
    {
        return $this->translations;
    }

    /**
     * Add translation
     *
     * @param ProductTranslation
     */
    public function addTranslation($translation)
    {
        if ($translation->getContent()) {
            $translation->setObject($this);
            $this->translations->add($translation);
        }
    }



    /**
     * Remove translation
     *
     * @param ProductTranslation
     */
    public function removeTranslation($translation)
    {
        $this->translations->removeElement($translation);
    }



}
