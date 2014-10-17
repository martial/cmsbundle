<?php

    namespace scrclub\CMSBundle\Extensions;

    use Doctrine\Common\Collections\ArrayCollection;
    use Doctrine\Common\Collections\Collection;
    use scrclub\CMSBundle\Entity\Node;


    class CMSUtilsExtension extends \Twig_Extension
    {

        /**
         * @return array
         */
        public function getFunctions()
        {
            return array(
                'nodeContainsMedia' => new \Twig_Function_Method($this, 'nodeContainsMedia'),
                'getTextContent' => new \Twig_Function_Method($this, 'getTextContent'),
                'getBooleanContent' => new \Twig_Function_Method($this, 'getBooleanContent'),
                'getCategories' => new \Twig_Function_Method($this, 'getCategories'),
                'filterByGMapRegion' => new \Twig_Function_Method($this, 'filterByGMapRegion'),

            );
        }




        public function nodeContainsMedia(Node $node, $media ) {


            return $node->getAllMedias()->contains($media);

        }

        public function getTextContent(Node $node, $type) {

            foreach($node->getTextContent() as $text ) {

                if($text->getType() == $type)
                    return $text->getText();

            }

            return "";

        }

        public function getBooleanContent(Node $node, $type) {

            foreach($node->getBooleanContent() as $text ) {

                if($text->getType() == $type)
                    return $text->getText();

            }

            return "";

        }

        public function getCategories ($nodes, $sortByName = true) {

            $result = new ArrayCollection();

            foreach ($nodes as $node) {

                $categories = $node->getCategories();

                foreach ($categories as $category) {

                    if( !$result->contains($category)) {
                        $result->add($category);
                    }
                }

            }

            $result = $result->toArray();
            
            if($sortByName)
                $this->sortByName($result);

            return $result;

        }

        public function sortByName (&$nodes) {
            return usort( $nodes, array($this,'nameSort') );

        }

        private static  function nameSort( $a, $b ) {

            return strcmp($a->getName(), $b->getName());



        }

        public function filterByGMapRegion ($nodes) {


            $result = array();
            foreach($nodes as $node ) {

                $gMapData = $node->getGMapData();
                if($gMapData) {


                $region = $gMapData->getRegionShort();

                   // echo $region;

                if (!array_key_exists($region, $result)) {

                    $result[$region] = array();

                }

                array_push( $result[$region], $node);

                }

            }


            ksort($result);

            return $result;

        }




        /**
         * @return string
         */
        public function getName()
        {
            return 'cmsutils';
        }
    }