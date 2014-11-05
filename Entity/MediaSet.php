<?php

namespace scrclub\CMSBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MediaSet
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="scrclub\CMSBundle\Entity\MediaSetRepository")
 */
class MediaSet
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer"))
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;


    /**
     * @var string
     *
     * @ORM\Column(name="max", type="integer", options={"default" = 0}, nullable=true)
     */
    private $max;


    /**
     * @ORM\ManyToMany(targetEntity="scrclub\CMSBundle\Entity\Media", cascade={"persist"})
     */
    private $medias;


    public $mediasFiltered;


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
     * @return MediaSet
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
     * Set max
     *
     * @param string $max
     * @return MediaSet
     */
    public function setMax($max)
    {
        $this->max = $max;
    
        return $this;
    }

    /**
     * Get max
     *
     * @return string 
     */
    public function getMax()
    {
        return $this->max;
    }



    public function addMedia(\scrclub\CMSBundle\Entity\Media $medias)
    {
        // Ici, on utilise l'ArrayCollection vraiment comme un tableau, avec la syntaxe []
        $this->medias[] = $medias;
    }

    /*
     * Remove categories
     *
     * @param scrClub\CMSBundle\Entity\MediaSet $categories
     */

    public function removeMedia(\scrclub\CMSBundle\Entity\Media $media)
    {
        // Ici on utilise une méthode de l'ArrayCollection, pour supprimer la catégorie en argument
        $this->medias->removeElement($media);
    }

    public function setMedias($medias) {
        $this->medias = $medias;
    }

    public function getMedias() {
        return $this->medias;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->medias = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function __toString() {

        return $this->getName();
    }
    
}