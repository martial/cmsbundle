<?php

namespace scrclub\CMSBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * Category
 *
 * @ORM\Table()
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="scrclub\CMSBundle\Entity\MediaContentTypeRepository")
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 */

class MediaContentType extends ContentType
{

    public function __construct()
    {
        $this->setType("media");
        $this->medias = new ArrayCollection();

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
     * @ORM\ManyToMany(targetEntity="scrclub\CMSBundle\Entity\Media", cascade={"persist"})
     * @ORM\OrderBy({"level" = "ASC"})
     */
    private $medias;


    /**
     * @Gedmo\Locale
     * Used locale to override Translation listener`s locale
     * this is not a mapped field of entity metadata, just a simple property
     */
    protected $locale;


    public function addMedia(\scrclub\CMSBundle\Entity\Media $medias)
    {
        $this->medias[] = $medias;
    }

    /*
     * R
     *
     * @param scrClub\CMSBundle\Entity\MediaSet
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

    public function getId() {
        return $this->id;
    }


    public function setTranslatableLocale($locale)
    {
        $this->locale = $locale;
    }





}
