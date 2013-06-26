<?php

    namespace scrclub\CMSBundle\Extensions;

    use Doctrine\Common\Collections\ArrayCollection;
    use Doctrine\Common\Collections\Collection;


    class GoogleMapsExtension extends \Twig_Extension
    {
        /**
         * @return array
         */
        public function getFunctions()
        {
            return array(
                'getTownDepartment' => new \Twig_Function_Method($this, 'getTownDepartment'),

            );
        }



        public function getTownDepartment($lat = null, $long = null )
        {

            if(!$lat OR !$long)
                return "";


            $fullurl = "http://maps.googleapis.com/maps/api/geocode/json?latlng=".$lat.",".$long."&sensor=false";
            $string = file_get_contents($fullurl); // get json content
            $json_a = json_decode($string, true); //json decoder

            $result = "";


            foreach ($json_a['results'][0]['address_components'] as $comp ) {

                 if($comp['types'][0] == "locality" ) {
                     $result .= $comp['long_name']." ";
                 }

                if($comp['types'][0] == "postal_code" ) {
                    $result .= "(".$comp['long_name'].")";
                }

            }



            return $result;

        }



        /**
         * @return string
         */
        public function getName()
        {
            return 'ggMapsExt';
        }
    }