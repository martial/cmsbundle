<?php
// src/Acme/DemoBundle/Entity/Document.php
    namespace scrclub\CMSBundle\Entity;

    use Doctrine\ORM\Mapping as ORM;
    use Symfony\Component\Validator\Constraints as Assert;

    /**
     * @ORM\Entity
     */
    class EmbeddedDocument extends Media
    {


        /**
         *
         * Original URL of file
         *
         * @ORM\Column(type="string", length=255, nullable=true)
         */
        public $url;


        /**
         *
         * Type of embed ( you tube / vimeo etc.. )
         *
         * @ORM\Column(type="string", length=255, nullable=true)
         */
        public $embedType;

        /**
         *
         * Id of media isolated
         *
         * @ORM\Column(type="string", length=255, nullable=true)
         */
        public $embedId;


        /**
         *
         * If available add a thumbnail to embedded document :)
         *
         * @ORM\ManyToOne(targetEntity="scrclub\CMSBundle\Entity\Media")
         * @ORM\JoinColumn(name="media_id", referencedColumnName="id", nullable=true)
         */
        public $thumbnail;


        public function getEmbedCode() {

        }

        public function __toString() {

            return "embedded";

        }

        public function setEmbedId($embedId) {
            $this->embedId = $embedId;
        }

        public function getEmbedId() {
            return $this->embedId;
        }

        public function setEmbedType($embedType) {
            $this->embedType = $embedType;
        }

        public function getEmbedType() {
            return $this->embedType;
        }

        public function setThumbnail($thumbnail) {
            $this->thumbnail = $thumbnail;
        }

        public function getThumbnail() {
            return $this->thumbnail;
        }

        public function setUrl($url) {
            $this->url = $url;
        }

        public function getUrl() {
            return $this->url;
        }


    }