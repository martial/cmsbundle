<?php

    namespace scrclub\CMSBundle\Extensions;
    use scrclub\CMSBundle\Entity\EmbeddedDocument;
    use scrclub\CMSBundle\Entity\Media;

    /**
     *
     * Utils for Twig to get embed
     *
     */
    class EmbedExtension extends \Twig_Extension {

        /**
         * @return array
         */
        public function getFunctions() {
            return array('getEmbedFromUrl' => new \Twig_Function_Method($this, 'getEmbedFromUrl', array('is_safe' => array('html'))),
                         'getEmbed' => new \Twig_Function_Method($this, 'getEmbed', array('is_safe' => array('html'))),
                         'getThumbnail' => new \Twig_Function_Method($this, 'getThumbnail', array('is_safe' => array('html')))

            );
        }

        public function printMediaThumbnail(Media $media) {

            $result = "";

            switch ($media->getType()) {

                case 'image' :

                    //$result = "<img src='".$media->get."' >"

                    break;

                case 'document':
                    break;

                case 'embedded':
                    break;



            }


        }


        /*
         *
         * get embed thumbnail if available
         *
         *
         * @return string
         */

        public function getThumbnail (EmbeddedDocument $doc, $quality = 100) {

            $embedUtils = new \scrclub\CMSBundle\Service\EmbeddedUtils();
            $thumb = $embedUtils->getEmbedThumbnail($doc, $quality);
            unset($service);

            return $thumb;

        }

        /*
         *
         * get embed code for third party services
         *
         *
         * @return string
         */

        public function getEmbed (EmbeddedDocument $doc, $width = 320, $height = 240, $autoplay = false) {




            $result = "";

            switch ($doc->getEmbedType()) {

                case "youtube" :

                    $result = '<iframe id="ytplayer" type="text/html" width="'.$width.'" height="'.$height.'" src="http://www.youtube.com/embed/'.$doc->getEmbedId().'?autoplay='.$autoplay.'" frameborder="0"/>';
                    break;

                case "vimeo" :

                    $result = '<iframe src="http://player.vimeo.com/video/'.$doc->getEmbedId().'" width="'.$width.'" height="'.$height.'" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>';
                    break;

                case "image" :

                    $result = '<img src="'.$doc->getUrl().'">';

                    break;

                case "dailymotion":

                    $xml = simplexml_load_file("http://www.dailymotion.com/services/oembed?format=xml&url=".$doc->getUrl());

                    $result = $xml->html;

                    break;

                case "soundcloud" :

                    // first resolve us

                    $json =  file_get_contents("http://soundcloud.com/oembed?format=json&url=".$doc->getUrl()."&iframe=true");
                    $result = json_decode($json);

                    //var_dump($result);

                    $result = $result->html;


                    break;

                case "file" :

                    $name = $doc->getName();
                    if(empty($name))
                        $name  = $doc->getUrl();

                    $result = '<a href="'.$doc->getUrl().'">'.$name.'</a>';

                    break;

            }

            return $result;


        }


        /*
         *
         * get embed code for third party services
         *
         * @return string
         */

        public function getEmbedFromUrl ($url, $service = null) {


            if(!$service) {
                $embedUtils = new \scrClub\CMSBundle\Service\EmbeddedUtils();
                $service = $embedUtils->guessService($url);
                unset($service);
            }

            $result = "";

            switch ($service) {

                case "youtube" :

                    $id = $this->getIdFromUrl($url, $service);
                    if($id)
                        $result = '<iframe id="ytplayer" type="text/html" width="640" height="390" src="http://www.youtube.com/embed/'.$id.'?autoplay=1&origin=http://example.com" frameborder="0"/>';
                    break;

            }

            return $result;

        }



        /**
         * @return string
         */
        public function getName() {
            return 'EmbedExtension';
        }
    }