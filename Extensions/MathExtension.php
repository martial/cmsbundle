<?php

    namespace scrclub\CMSBundle\Extensions;



    class MathExtension extends \Twig_Extension
    {
        /**
         * @return array
         */
        public function getFunctions()
        {
            return array();
        }

        public function getFilters() {

            return array(

                'ceil' => new \Twig_Filter_Method($this, 'ceil'),
                'floor' => new \Twig_Filter_Method($this, 'floor')
            );

        }


        public function ceil($value )
        {
            return ceil($value);

        }

        
        public function floor($value) {
	        
	        return floor($value);


        }



        /**
         * @return string
         */
        public function getName()
        {
            return 'math';
        }
    }