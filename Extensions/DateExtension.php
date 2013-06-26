<?php

    namespace scrclub\CMSBundle\Extensions;

    use Doctrine\Common\Collections\ArrayCollection;
    use Doctrine\Common\Collections\Collection;


    class DateExtension extends \Twig_Extension
    {
        /**
         * @return array
         */
        public function getFunctions()
        {
            return array(
                'getYear' => new \Twig_Function_Method($this, 'getYear'),

            );
        }

        public function getYear(\DateTime $date) {

            return  $date->format("Y");

        }




        /**
         * @return string
         */
        public function getName()
        {
            return 'array_ext';
        }
    }