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
     * @ORM\DiscriminatorMap({"image" = "Image"})
     */
    class Document extends Media {

        /**
         * @ORM\Column(type="string", length=255, nullable=true)
         *
         */
        public $extension;

        /**
         * @ORM\Column(type="string", length=255, nullable=true)
         *
         */
        public $originalName;

        /**
         * @ORM\Column(type="integer", nullable=true)
         *
         */
        public $fileSize;


        /**
         * @ORM\Column(type="string", length=255, nullable=true)
         */
        public $path;

        /**
         * @Assert\File(maxSize="6000000")
         */
        protected $file;


        public function getAbsolutePath() {
            return null === $this->path ? null : $this->getUploadRootDir() . '/' . $this->path;
        }

        public function getWebPath() {
            return null === $this->path ? null : $this->getUploadDir() . '/' . $this->path;
        }

        protected function getUploadRootDir() {
            // le chemin absolu du répertoire où les documents uploadés doivent être sauvegardés
            return __DIR__ . '/../../../../web/' . $this->getUploadDir();
        }

        protected function getUploadDir() {
            // on se débarrasse de « __DIR__ » afin de ne pas avoir de problème lorsqu'on affiche
            // le document/image dans la vue.
            return 'uploads/documents';
        }


        /**
         * @ORM\PrePersist()
         * @ORM\PreUpdate()
         */
        public function preUpload() {

            if (null !== $this->file) {

                $this->path = sha1(uniqid(mt_rand(), true)) . 'ahou.' . $this->file->guessExtension();
            }
        }

        /**
         * @ORM\PostPersist()
         * @ORM\PostUpdate()
         */
        public function upload() {

            if (null === $this->file) {
                return;
            }

            // remove file is there's one
            $this->removeUpload();
            $this->preUpload();

            // s'il y a une erreur lors du déplacement du fichier, une exception
            // va automatiquement être lancée par la méthode move(). Cela va empêcher
            // proprement l'entité d'être persistée dans la base de données si
            // erreur il y a
            $this->file->move($this->getUploadRootDir(), $this->path);

            unset($this->file);
        }

        /**
         * @ORM\PostRemove()
         */
        public function removeUpload()
        {
            $path = $this->getAbsolutePath();
            if (!empty($path)) {
                @unlink($this->getAbsolutePath());
            }
        }

        public function setPath($path) {
            $this->path = $path;
        }

        public function getPath() {
            return $this->path;
        }



        public function setFile($file) {
            $this->file = $file;
        }

        public function getFile() {
            return $this->file;
        }


        public function setExtension($extension) {
            $this->extension = $extension;
        }

        public function getExtension() {
            return $this->extension;
        }

        public function setOriginalName($originalName) {
            $this->originalName = $originalName;
        }

        public function getOriginalName() {
            return $this->originalName;
        }

        public function setFileSize($fileSize) {
            $this->fileSize = $fileSize;
        }

        public function getFileSize() {
            return $this->fileSize;
        }

        public function __toString() {

            return "document";

        }


    }