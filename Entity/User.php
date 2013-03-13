<?php
// src/Acme/UserBundle/Entity/User.php

    namespace scrclub\CMSBundle\Entity;

    use FOS\UserBundle\Entity\User as BaseUser;
    use Doctrine\ORM\Mapping as ORM;

    /**
     * @ORM\Entity
     * @ORM\Table(name="User")
     */
    class User extends BaseUser
    {
        /**
         * @ORM\Id
         * @ORM\Column(type="integer")
         * @ORM\GeneratedValue(strategy="AUTO")
         */
        protected $id;


        /**
         * @ORM\OneToOne(targetEntity="Image", cascade={"persist"})
         * @ORM\JoinColumn(nullable=false)
         */
        private $image;


        public function __construct()
        {
            parent::__construct();
            // your own logic
        }

        public function setImage($image) {
            $this->image = $image;
        }

        public function getImage() {
            return $this->image;
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
}