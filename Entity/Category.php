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

class Category implements Translatable
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
    private $id;

    /**
     * @var string
     *
     * @Gedmo\Translatable
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     *
     */
    private $name;

    /**
     * @var string
     * @Gedmo\Translatable
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @Gedmo\Locale
     * Used locale to override Translation listener`s locale
     * this is not a mapped field of entity metadata, just a simple property
     */
    protected $locale;

    /**
     * @ORM\OneToMany(
     *  targetEntity="scrclub\CMSBundle\Entity\Translation\CategoryTranslation",
     *  mappedBy="object",
     *  cascade={"persist", "remove"}
     * )
     * @Assert\Valid(deep = true)
     */
    protected $translations;


    /**
     * @ORM\ManyToMany(targetEntity="scrclub\CMSBundle\Entity\Node", cascade={"persist"})
     */
    private $nodes;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Category
     */
    public function setName($name)
    {
        $this->name = $name;
    
        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set descr
     *
     * @param string $descr
     * @return Category
     */
    public function setDescription($description)
    {
        $this->description = $description;
    
        return $this;
    }

    /**
     * Get descr
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }


    /**
     * Set translations
     *
     * @param ArrayCollection $translations
     * @return ArrayCollection
     */
    public function setTranslations($translations)
    {
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
     * @param $translation
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
     * @param $translation
     */
    public function removeTranslation($translation)
    {
        $this->translations->removeElement($translation);
    }

    public function setTranslatableLocale($locale)
    {
        $this->locale = $locale;
    }


    public function addNode(\scrclub\CMSBundle\Entity\Node $node)
    {
        // Ici, on utilise l'ArrayCollection vraiment comme un tableau, avec la syntaxe []
        $this->nodes[] = $node;
    }

    /**
     * Remove categories
     *
     * @param scrClub\CMSBundle\Entity\Node $node
     */
    public function removeNode(\scrclub\CMSBundle\Entity\Node $node)
    {
        // Ici on utilise une méthode de l'ArrayCollection, pour supprimer la catégorie en argument
        $this->nodes->removeElement($node);
    }

    public function setNodes($nodes) {
        $this->nodes = $nodes;
    }

    public function getNodes() {
        return $this->nodes;
    }

    public function __toString()
    {
        return $this->getName();
    }


}
