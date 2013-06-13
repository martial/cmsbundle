<?php

namespace scrclub\CMSBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;


use Gedmo\Translatable\Translatable;
use Symfony\Component\Validator\Constraints as Assert;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints\NotBlank;


/**
 * scrclub\CMSBundle\Entity\Node
 *
 * @ORM\Table()
 * @ORM\Entity
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\DiscriminatorMap({ "node" = "Node", "post" = "Post" })
 * @Gedmo\Tree(type="nested")
 * @ORM\Table(name="ext_nodes")
 * @Gedmo\TranslationEntity(class="scrclub\CMSBundle\Entity\Translation\NodeTranslation")
 * @ORM\Entity(repositoryClass="scrclub\CMSBundle\Entity\NodeRepository")
 *
 */
class Node
{

    public function __construct()
    {
        $this->translations = new ArrayCollection();;
        $this->mediasets = new ArrayCollection();
        $this->mediaNodes = new ArrayCollection();
    }

    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string $type
     * @ORM\Column(name="type", type="string", nullable=true, length=255)
     */
    protected $type;

    /**
     * @Gedmo\Locale
     * Used locale to override Translation listener`s locale
     * this is not a mapped field of entity metadata, just a simple property
     */
    protected $locale;

    /**
     * @var integer $active
     *
     * @ORM\Column(name="active", type="boolean")
     */
    protected $active;

    /**
     * @var string $name
     * @Gedmo\Translatable
     * @ORM\Column(name="name", type="string", nullable=true, length=255)
     */
    protected $name;


    /**
     * @var string $header
     * @Gedmo\Translatable
     * @ORM\Column(name="header", type="text", length=4096, nullable=true)
     */
    protected $header;

    /**
     * @param string $header
     */
    public function setHeader($header) {
        $this->header = $header;
    }

    /**
     * @return string
     */
    public function getHeader() {
        return $this->header;
    }

    /**
     * @var string $description
     * @Gedmo\Translatable
     * @ORM\Column(name="description", type="text", length=4096, nullable=true)
     */
    protected $description;



    /**
     * @ORM\OneToMany(
     *  targetEntity="scrclub\CMSBundle\Entity\Translation\NodeTranslation",
     *  mappedBy="object",
     *  cascade={"persist", "remove"}
     * )
     * @Assert\Valid(deep = true)
     */
    protected $translations;

    /**
     * @Gedmo\TreeLeft
     * @ORM\Column(name="lft", type="integer")
     */
    protected $lft;

    /**
     * @Gedmo\TreeLevel
     * @ORM\Column(name="lvl", type="integer")
     */
    public $lvl;

    /**
     * @Gedmo\TreeRight
     * @ORM\Column(name="rgt", type="integer")
     */
    protected $rgt;

    /**
     * @Gedmo\TreeRoot
     * @ORM\Column(name="root", type="integer", nullable=true)
     */
    public $root;

    /**
     * @Gedmo\TreeParent
     * @ORM\ManyToOne(targetEntity="Node", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="SET NULL")
     */
    protected $parent;

    /**
     * @ORM\OneToMany(targetEntity="Node", mappedBy="parent")
     * @ORM\OrderBy({"lft" = "ASC"})
     */
    protected  $children;

    /**

     * @Gedmo\Slug(fields={"name"})
     * @ORM\Column(length=128, nullable=true, unique=true)
     */

    protected $slug;

    /**
     * @var string $fullslug
     * @ORM\Column(name="fullslug", type="string", nullable=true, length=255)
     */
    protected $fullslug;


    /**
     * @ORM\ManyToOne(targetEntity="scrclub\CMSBundle\Entity\Template")
     * @ORM\JoinColumn()
     */
    protected $template;

    /**
     * @ORM\ManyToMany(targetEntity="scrclub\CMSBundle\Entity\MediaSet", cascade={"persist"})
     */
    private $mediasets;


    /**
     * @ORM\ManyToMany(targetEntity="scrclub\CMSBundle\Entity\Document", cascade={"persist"})
     * */

    private $medias;


    /**
     * @ORM\OneToMany(targetEntity="scrclub\CMSBundle\Entity\MediaNode", mappedBy="node", cascade={"persist", "merge"})
     * @ORM\OrderBy({"level" = "ASC"})
     */
    protected $mediaNodes;


    /**
     * @var string $metakey
     * @ORM\Column(name="metakey", type="string", nullable=true, length=255)
     */
    protected $metakey;

    /**
     * @var string $metadescr
     * @ORM\Column(name="metadescr", type="string", nullable=true, length=255)
     */
    protected $metadescr;

    /**
     * @ORM\ManyToMany(targetEntity="scrclub\CMSBundle\Entity\Category", cascade={"persist"})
     */
    private $categories;

    /**
     * @var string
     *
     * @ORM\Column(name="latitude", type="string", length=255, nullable=true)
     */
    private $latitude;

    /**
     * @param string $latitude
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;
    }

    /**
     * @return string
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * @var string
     *
     * @ORM\Column(name="longitude", type="string", length=255, nullable=true)
     */
    private $longitude;

    /**
     * @param string $longitude
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;
    }

    /**
     * @return string
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

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
     * Set type
     *
     * @param string type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set active
     *
     * @param integer $active
     * @return Node
     */
    public function setActive($active)
    {
        $this->active = $active;
    
        return $this;
    }

    /**
     * Get active
     *
     * @return integer 
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Node
     */
    public function setName($name)
    {
        $this->name = $name;
    
        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Node
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    public function setTranslatableLocale($locale)
    {
        $this->locale = $locale;
    }


    public function setParent($parent = null)
    {
        $this->parent = $parent;
    }


    public function getParent()
    {
        return $this->parent;
    }

    public function getChildren()
    {
        return $this->children;
    }

    public function setLft($lft = null)
    {
        $this->lft = $lft;
    }


    public function getLft()
    {
        return $this->lft;
    }

    public function setRgt($rgt = null)
    {
        $this->rgt = $rgt;
    }


    public function getRgt()
    {
        return $this->rgt;
    }

    /**
     * Set translations
     *
     * @param ArrayCollection $translations
     * @return Node
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

    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
       // $metadata->addPropertyConstraint('name', new NotBlank());
    }

    public function setSlug($slug) {
        $this->slug = $slug;
    }

    public function getSlug() {
        return $this->slug;
    }

    public function setPosts($posts) {
        $this->posts = $posts;
    }

    public function getPosts() {
        return $this->posts;
    }

    /**
     * @param string $fullslug
     */
    public function setFullslug($fullslug) {
        $this->fullslug = $fullslug;
    }

    /**
     * @return string
     */
    public function getFullslug() {
        return $this->fullslug;
    }

    public function setTemplate($template) {
        $this->template = $template;
    }

    public function getTemplate() {
        return $this->template;
    }

    /**
     * @param string $metadescr
     */
    public function setMetadescr($metadescr) {
        $this->metadescr = $metadescr;
    }

    /**
     * @return string
     */
    public function getMetadescr() {
        return $this->metadescr;
    }

    /**
     * @param string $metakey
     */
    public function setMetakey($metakey) {
        $this->metakey = $metakey;
    }

    /**
     * @return string
     */
    public function getMetakey() {
        return $this->metakey;
    }



    public function addMedia(\scrclub\CMSBundle\Entity\Media $media)
    {
        // Ici, on utilise l'ArrayCollection vraiment comme un tableau, avec la syntaxe []
        $this->medias[] = $media;
    }

    /**
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
        $this->medias = medias;
    }

    public function getMedias() {
        return $this->medias;
    }




    public function addMediaSet(\scrclub\CMSBundle\Entity\MediaSet $mediaset)
    {
        // Ici, on utilise l'ArrayCollection vraiment comme un tableau, avec la syntaxe []
        $this->mediasets[] = $mediaset;
    }

    /**
     * Remove categories
     *
     * @param scrClub\CMSBundle\Entity\MediaSet $categories
     */
    public function removeMediaSet(\scrclub\CMSBundle\Entity\MediaSet $mediaset)
    {
        // Ici on utilise une méthode de l'ArrayCollection, pour supprimer la catégorie en argument
        $this->mediasets->removeElement($mediaset);
    }

    public function setMediasets($mediasets) {
        $this->mediasets = $mediasets;
    }

    public function getMediasets() {
        return $this->mediasets;
    }




    public function addMediaNode(\scrclub\CMSBundle\Entity\MediaNode $mediaNode)
    {
        // Ici, on utilise l'ArrayCollection vraiment comme un tableau, avec la syntaxe []
        $this->mediaNodes[] = $mediaNode;
    }

    /**
     * Remove categories
     *
     * @param scrClub\CMSBundle\Entity\MediaSet $categories
     */
    public function removemediaNode(\scrclub\CMSBundle\Entity\MediaNode $mediaNode)
    {
        // Ici on utilise une méthode de l'ArrayCollection, pour supprimer la catégorie en argument
        $this->mediaNodes->removeElement($mediaNode);
    }

    public function setMediaNodes($mediaNodes) {
        $this->mediaNodes = $mediaNodes;
    }

    public function getMediaNodes() {
        return $this->mediaNodes;
    }



    public function addCategory(\scrclub\CMSBundle\Entity\Category $category)
    {
        // Ici, on utilise l'ArrayCollection vraiment comme un tableau, avec la syntaxe []
        $this->categories[] = $category;
    }

    /**
     * Remove categories
     *
     * @param scrClub\CMSBundle\Entity\Category $category
     */
    public function removeCategory(\scrclub\CMSBundle\Entity\Category $category)
    {
        // Ici on utilise une méthode de l'ArrayCollection, pour supprimer la catégorie en argument
        $this->mediasets->removeElement($category);
    }

    public function setCategories($categories) {
        $this->mediasets = $categories;
    }

    public function getCategories() {
        return $this->categories;
    }



    /**
     * Set lvl
     *
     * @param integer $lvl
     * @return Node
     */
    public function setLvl($lvl)
    {
        $this->lvl = $lvl;
    
        return $this;
    }

    /**
     * Get lvl
     *
     * @return integer 
     */
    public function getLvl()
    {
        return $this->lvl;
    }

    /**
     * Set root
     *
     * @param integer $root
     * @return Node
     */
    public function setRoot($root)
    {
        $this->root = $root;
    
        return $this;
    }

    /**
     * Get root
     *
     * @return integer 
     */
    public function getRoot()
    {
        return $this->root;
    }

    /**
     * Add children
     *
     * @param \scrclub\CMSBundle\Entity\Node $children
     * @return Node
     */
    public function addChildren(\scrclub\CMSBundle\Entity\Node $children)
    {
        $this->children[] = $children;
    
        return $this;
    }

    /**
     * Remove children
     *
     * @param \scrclub\CMSBundle\Entity\Node $children
     */
    public function removeChildren(\scrclub\CMSBundle\Entity\Node $children)
    {
        $this->children->removeElement($children);
    }


}