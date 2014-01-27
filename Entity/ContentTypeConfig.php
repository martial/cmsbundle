<?php

namespace scrclub\CMSBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * ContentTypeConfig
 *
 * @ORM\Table()
 * @ORM\Entity
 */

class ContentTypeConfig
{

    public function __construct() {

        $this->categories = new ArrayCollection();

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
     * @param string $cascade
     */
    public function setCascade($cascade) {
        $this->cascade = $cascade;
    }

    /**
     * @return string
     */
    public function getCascade() {
        return $this->cascade;
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

    public function addCategory(\scrclub\CMSBundle\Entity\Category $category)
    {
        // Ici, on utilise l'ArrayCollection vraiment comme un tableau, avec la syntaxe []
        $this->categories[] = $category;
    }


    public function removeCategory(\scrclub\CMSBundle\Entity\Category $category)
    {
        // Ici on utilise une méthode de l'ArrayCollection, pour supprimer la catégorie en argument
        $this->categories->removeElement($category);
    }

    public function setCategories($categories) {
        $this->categories = $categories;
    }

    public function getCategories() {
        return $this->categories;
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
     * @ORM\Column(name="type", type="string", length=255, nullable=true)
     *
     */
    protected $type;

    /**
     * @var bool
     * @ORM\Column(name="cscd", type="boolean")
     *
     */
    protected $cascade;



    /**
     * @ORM\ManyToMany(targetEntity="scrclub\CMSBundle\Entity\Category", cascade={"persist"})
     */
    private $categories;




}
