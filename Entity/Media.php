<?php
// src/Acme/DemoBundle/Entity/Document.php
    namespace scrclub\CMSBundle\Entity;

    use Doctrine\ORM\Mapping as ORM;
    use Symfony\Component\Validator\Constraints as Assert;
    use Symfony\Component\HttpFoundation\File\UploadedFile;

    /**
     * @ORM\Entity
     * @ORM\InheritanceType("JOINED")
     * @ORM\DiscriminatorColumn(name="discr", type="string")
     * @ORM\DiscriminatorMap({"document" = "Document", "media" = "Media", "image" = "Image", "embedded" = "EmbeddedDocument" })
     */
    class Media {

        /**
         * Constructor
         */
        public function __construct() {
            $this->nodes = new \Doctrine\Common\Collections\ArrayCollection();
        }

        /**
         * Get id
         *
         * @return integer
         */
        public function getId() {
            return $this->id;
        }


        /**
         * @ORM\Id
         * @ORM\Column(type="integer")
         * @ORM\GeneratedValue(strategy="AUTO")
         */
        public $id;

        /**
         * @ORM\Column(type="text", length=4096, nullable=true)
         *
         */
        public $name;

        /**
         * @ORM\Column(type="string", length=1020, nullable=true)
         *
         */
        public $type;

        /**
         * @ORM\OneToMany(targetEntity="scrclub\CMSBundle\Entity\MediaNode", mappedBy="media", cascade={"remove"} )
         */
        protected $mediaNodes;

        /**
         * @ORM\ManyToMany(targetEntity="scrclub\CMSBundle\Entity\MediaSet", mappedBy="medias" , cascade={"persist"} )
         */
        protected $mediaSets;


        /**
         * @var string
         *
         * @ORM\Column(name="level", type="integer", options={"default" = 0})
         */
        private $level;


        public function setName($name) {
            $this->name = $name;
        }

        public function getName() {
            return $this->name;
        }


        public function addNode(\scrclub\CMSBundle\Entity\Node $node) {
            $this->nodes[] = $node;
        }

        /*
         * Remove node
         *
         * @param scrClub\CMSBundle\Entity\Node $node
         */
        public function removeNode(\scrclub\CMSBundle\Entity\Node $node) {
            $this->nodes->removeElement($node);
        }

        public function setNodes($nodes) {
            $this->nodes = $nodes;
        }

        public function getNodes() {
            return $this->nodes;
        }

        public function addMediaNode(\scrclub\CMSBundle\Entity\MediaNode $mediaNode) {
            // Ici, on utilise l'ArrayCollection vraiment comme un tableau, avec la syntaxe []
            $this->mediaNodes[] = $mediaNode;
        }

        /*
         * Remove categories
         *
         * @param scrClub\CMSBundle\Entity\MediaSet $categories
         */
        public function removemediaNode(\scrclub\CMSBundle\Entity\MediaNode $mediaNode) {
            // Ici on utilise une méthode de l'ArrayCollection, pour supprimer la catégorie en argument
            $this->mediaNodes->removeElement($mediaNode);
        }

        public function setMediaNodes($mediaNodes) {
            $this->mediaNodes = $mediaNodes;
        }

        public function getMediaNodes() {
            return $this->mediaNodes;
        }


        public function addMediaSet(\scrclub\CMSBundle\Entity\MediaSet $mediaSet) {
            // Ici, on utilise l'ArrayCollection vraiment comme un tableau, avec la syntaxe []
            $this->mediaSets[] = $mediaSet;
        }

        /*
         * Remove categories
         *
         * @param scrClub\CMSBundle\Entity\MediaSet $categories
         */
        public function removeMediaSet(\scrclub\CMSBundle\Entity\MediaSet $mediaSet) {
            // Ici on utilise une méthode de l'ArrayCollection, pour supprimer la catégorie en argument
            $this->mediaSets->removeElement($mediaSet);
        }

        public function setMediaSets($mediaNodes) {
            $this->mediaSets = $mediaNodes;
        }

        public function getMediaSets() {
            return $this->mediaSets;
        }

        public function setType($type) {
            $this->type = $type;
        }

        public function getType() {
            return $this->type;
        }

        /**
         * @param string $level
         */
        public function setLevel($level) {
            $this->level = $level;
        }

        /**
         * @return string
         */
        public function getLevel() {
            return $this->level;
        }




    }