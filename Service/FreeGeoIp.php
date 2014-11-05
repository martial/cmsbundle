<?php
// src/Sdz/BlogBundle/Service/SdzAntispam.php

    namespace scrclub\CMSBundle\Service;

    /**
     * Un anti-spam simple pour Symfony2.
     *
     * @author Leglopin
     */
    class FreeGeoIp
    {

        function __construct(){
        }

        public function getLocation () {

            $ip = $_SERVER['REMOTE_ADDR'];

            if($ip == "::1") {
                $ip = "92.157.117.248";
            }

            ini_set('default_socket_timeout', 5);
            $location = @file_get_contents('http://freegeoip.net/json/'.$ip);

            if($location === FALSE) {

            return NULL;

            } else {
                $result = json_decode($location);
                return $result;
            }

        }

        public function isInFrance() {

            $location = $this->getLocation();
            if($location == NULL) return true;

            if($location->country_code == "FR") return true;

            return false;

        }

        public function getCountry () {

        }

    }