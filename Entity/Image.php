<?php
// src/Acme/DemoBundle/Entity/Document.php
    namespace scrclub\CMSBundle\Entity;

    use Doctrine\ORM\Mapping as ORM;
    use Symfony\Component\Validator\Constraints as Assert;

    /**
     * @ORM\Entity
     */
    class Image extends Document
    {

        /**
         * @ORM\Column(type="integer")
         */
        public $width;

        /**
         * @ORM\Column(type="integer")
         */
        public $height;

        /**
         * @Assert\File(maxSize="10000000",
         * mimeTypes = {"image/jpeg", "image/png", "image/jpg", "image/tiff", "image/bmp"},
         * mimeTypesMessage = "error.mimetype.image"
         * )
         */

        protected $file;


        public function __toString() {

            return "image";

        }

        /**
         * @ORM\PostPersist()
         * @ORM\PostUpdate()
         */
        public function upload()
        {



            parent::upload();

            $this->setSize();

        }

        public function setHeight($height) {
            $this->height = $height;
        }

        public function getHeight() {
            return $this->height;
        }

        public function setWidth($width) {
            $this->width = $width;
        }

        public function getWidth() {
            return $this->width;
        }

        public function setSize() {

            $sizes = getimagesize($this->getWebPath());

            $this->setWidth($sizes[0]);
            $this->setHeight($sizes[1]);

        }

}