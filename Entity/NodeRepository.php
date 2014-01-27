<?php

namespace scrclub\CMSBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Gedmo\Tree\Entity\Repository\NestedTreeRepository;

class NodeRepository extends NestedTreeRepository
{

    private $tempSlug;

    public function generateFullSlug ($entity) {

        $this->tempSlug = $entity->getSlug();
        $this->recursiveSlug($entity);
        return  "/".$this->tempSlug;

    }

    private function recursiveSlug ($entity) {

        $parent = $entity->getParent();

        if ($parent) {
            $this->tempSlug = $parent->getSlug()."/".$this->tempSlug;
            $this->recursiveSlug($parent);
        }

    }



    /**
     * Used to define dynamically mediasets.
     * A node media set should herit from its parent.
     *
     * @param Node $node
     */

    //TODO herit cummulative

    public function getMediaSetsRecursive (Node $node) {

        $currentNode = $node;

       // echo $currentNode->getMediasets()->isEmpty();

        while( isset($currentNode) AND $currentNode->getMediasets()->isEmpty() ) {
            $currentNode = $currentNode->getParent();
            //echo $currentNode->getName();
            //echo $currentNode->getMediasets()->isEmpty();
        }

        //echo $currentNode->getName();

        if(isset($currentNode)) {
            $mediasets = $currentNode->getMediasets();

            foreach($mediasets as $mediaset ) {

                if(!$node->getMediasets()->contains($mediaset))
                    $node->addMediaSet($mediaset);

            }
        }


    }

    public function getFieldsRecursive (Node $node) {

        $currentNode = $node;

        // echo $currentNode->getMediasets()->isEmpty();

        while( isset($currentNode)  ) {
            $currentNode = $currentNode->getParent();

            if(isset($currentNode)) {
                $contentTypeConfigs = $currentNode->getContentTypeConfigs();

                foreach($contentTypeConfigs as $contentTypeConfig ) {

                    //echo  $contentTypeConfig->getName();

                    if(!$node->getContentTypeConfigs()->contains($contentTypeConfig))
                        $node->addContentTypeConfig($contentTypeConfig);

                }
            }

            //echo $currentNode->getName();
            //echo $currentNode->getMediasets()->isEmpty();
        }

        //echo $currentNode->getName();




    }



}
