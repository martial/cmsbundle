<?php
// src/Sdz/BlogBundle/Service/SdzAntispam.php

    namespace scrClub\CMSBundle\Service;
    use scrclub\CMSBundle\Entity\EmbeddedDocument;

    /**
     * Utils for third-party services as youtube vimeo etc
     *
     * @author Martial
     */
    class EmbeddedUtils
    {


        function __construct(){
        }




        public function getIdFromUrl ($url, $service) {

            $id = null;

            switch ($service) {

                case "youtube" :
                    preg_match(
                        '/[\\?\\&]v=([^\\?\\&]+)/',
                        $url,
                        $matches
                    );
                    $id = $matches[1];
                    break;

                case "vimeo" :

                    $videoId =explode('vimeo.com/', $url);
                    $id = $videoId[1];

                    break;

                case "dailymotion":

                    $id = strtok(basename($url), '_');

                    break;

                case "soundcloud" :
                    $json =  file_get_contents("http://api.soundcloud.com/resolve.json?url=".$url."&client_id=076d8161221f48eaed0516b5215b5aab");
                    $result = json_decode($json);

                    $id = $result->id;

                    break;

            }


            return $id;


        }

        public function guessService($url) {


            //echo 'guess servitch';

            $urlParsed = parse_url($url);

            if(isset($urlParsed["host"])) {

                $host =  $urlParsed["host"];
                $service = $this->findServiceByHost($host);

                if($service)
                 return $service;

            }

            // check if file
            if ($this->remoteFileExists($url)) {

                $sizes = getimagesize($url);


                if ($sizes !== false)
                    return "image";
                else
                    return "file";
                    // It's an image

            }

            return null;

        }

        private function findServiceByHost($host) {

            $services = $this->getServices();

            foreach ( $services as $service) {

                $found = strrpos($host, $service);

                if($found !== false)
                    return $service;

            }

            return null;

        }

        public function getEmbedThumbnail ($doc, $quality) {



            // if that's an image always return the URL
            if($doc->getEmbedType() == 'image')
                return $doc->getUrl();




            $thumbs =  $this->getThumbnails($doc->getEmbedId(), $doc->getEmbedType());

            if(empty($thumbs))
                return "";

            $index = round($quality * ( count($thumbs) -1 ) / 100);



            return $thumbs[$index];

        }

        public function getEmbedThumbnails (EmbeddedDocument $doc) {

            return $this->getThumbnails($doc->getEmbedId(), $doc->getEmbedType());

        }

        public function getThumbnails ($id, $service) {

            $thumbnails = array();

           // array_push($thumbnails,"quette");

            switch ($service) {

                case "youtube":

                    $xml = simplexml_load_file("http://gdata.youtube.com/feeds/api/videos?q=".$id);

                    foreach ($xml->entry as $entry) {
                        // get nodes in media: namespace
                        $media = $entry->children('http://search.yahoo.com/mrss/');

                        // get video player URL
                        $attrs = $media->group->player->attributes();
                        $watch = $attrs['url'];

                        // get video thumbnail
                        $data['thumb_1'] = $media->group->thumbnail[0]->attributes(); // Thumbnail 1
                        $data['thumb_2'] = $media->group->thumbnail[1]->attributes(); // Thumbnail 2
                        $data['thumb_3'] = $media->group->thumbnail[2]->attributes(); // Thumbnail 3
                        $data['thumb_large'] = $media->group->thumbnail[3]->attributes(); // Large thumbnail

                        array_push($thumbnails, $data['thumb_1']);
                        array_push($thumbnails, $data['thumb_2']);
                        array_push($thumbnails, $data['thumb_3']);
                        array_push($thumbnails, $data['thumb_large']);

                    } // End foreach


                    break;

                case "vimeo":

                    $xml = simplexml_load_file("http://vimeo.com/api/v2/video/".$id.".xml");

                    array_push($thumbnails, $xml->video->thumbnail_small);
                    array_push($thumbnails, $xml->video->thumbnail_medium);
                    array_push($thumbnails, $xml->video->thumbnail_large);

                    break;

                case "dailymotion":

                    $xml = simplexml_load_file("http://www.dailymotion.com/services/oembed?format=xml&url=http://www.dailymotion.com/video/".$id);

                    array_push($thumbnails, $xml->thumbnail_url);

                    break;

                case "soundcloud" :


                    try {
                    $json =  file_get_contents("http://api.soundcloud.com/tracks/".$id.".json?client_id=076d8161221f48eaed0516b5215b5aab");
                    $result = json_decode($json);

                    array_push($thumbnails, $result->artwork_url);

                    } catch (\Exception $e) {

                    }

                    //$track_url = 'http://soundcloud.com/forss/flickermood';
                    //$embed_info = json_decode($client->get('oembed', array('url' => $track_url)));
                    break;


            }

           return $thumbnails;


        }

        private function remoteFileExists($url) {

            $curl = curl_init($url);

            //don't fetch the actual page, you only want to check the connection is ok
            curl_setopt($curl, CURLOPT_NOBODY, true);

            //do request
            $result = curl_exec($curl);

            $ret = false;

            //if request did not fail
            if ($result !== false) {
                //if request was ok, check response code
                $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

                if ($statusCode == 200) {
                    $ret = true;
                }
            }

            curl_close($curl);

            return $ret;
        }


        private function getServices () {

            return array("youtube", "vimeo", "soundcloud", 'dailymotion');

        }


    }