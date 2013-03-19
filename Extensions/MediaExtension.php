<?php

    namespace scrclub\CMSBundle\Extensions;

    use Doctrine\Common\Collections\ArrayCollection;
    use Doctrine\Common\Collections\Collection;
    use scrclub\CMSBundle\Entity\Node;
    use scrclub\CMSBundle\Entity\MediaNode;

    /**
     * Translate locales using intl extension
     * Usages:
     *  {{ getLocaleName('fr', 'es') }}
     */
    class MediaExtension extends \Twig_Extension
    {
        /**
         * @return array
         */
        public function getFunctions()
        {
            return array(
                'getRandomImage' => new \Twig_Function_Method($this, 'getRandomImage'),
            );
        }


        public function getRandomImage(Node $node, $mediasetName = NULL, $type = NULL)
        {

            $result = array();

            foreach($node->getMediaNodes() as $mediaNode) {

                // if media.mediaset contains a mediaset with this name, go rdm !

                $media = $mediaNode->getMedia();

                if($type AND $media->getType() != $type) continue;

                if($mediasetName) {

                foreach($media->getMediaSets() as $mediaset ) {

                    if($mediaset->getName() == $mediasetName )
                        array_push($result, $media);

                }

                } else {

                    array_push($result, $media);
                }


            }


            if(empty($result)) return NULL;

            $index =  floor(rand(0, count($result)-1));
            return $result[$index];


        }


        /**
         * @return string
         */
        public function getName()
        {
            return 'MediaExtension';
        }
    }