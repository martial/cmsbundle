<?php

    namespace scrclub\CMSBundle\Extensions;




    class StringExtension extends \Twig_Extension
    {
        /**
         * @return array
         */
        public function getFunctions()
        {
            return array(
                'linkText' => new \Twig_Function_Method($this, 'linkText'),

            );
        }


        public function linkText($string)
        {

            $replacement = '<a href="$1" target="_blank">$1</a>';
            $pattern = '/(http:\/\/[a-z0-9\.\/?=&]+)/i';

            $string = preg_replace($pattern, $replacement, $string);

            return $string;
        }



        /**
         * @return string
         */
        public function getName()
        {
            return 'stringextension';
        }
    }