<?php

    namespace scrclub\CMSBundle\Service;
    use Doctrine\Common\Collections\ArrayCollection;
    use scrclub\CMSBundle\Entity\Node;

    /**
     * Un anti-spam simple pour Symfony2.
     *
     * @author Leglopin
     */
    class NodeUtils {

        function __construct() {
        }

        public function setContentTypeOrder(Node &$node) {

            $result = new ArrayCollection();

            foreach ($node->getContentTypeConfigs() as $contentConfig) {

                $name = $contentConfig->getName();
                $type = $contentConfig->getType();

                foreach ($node->getTextContent() as $content) {
                    if ($name == $content->getName() AND $type == $content->getType()) {
                        $content->setOrder($contentConfig->getOrder());
                        $result->add($content);
                    }
                }

                foreach ($node->getMediaContent() as $content) {
                    if ($name == $content->getName() AND $type == $content->getType()) {
                        $content->setOrder($contentConfig->getOrder());
                        $result->add($content);
                    }
                }


                foreach ($node->getBooleanContent() as $content) {
                    if ($name == $content->getName() AND $type == $content->getType()) {
                        $content->setOrder($contentConfig->getOrder());
                        $result->add($content);
                    }

                }

                foreach ($node->getDateContent() as $content) {
                    if ($name == $content->getName() AND $type == $content->getType()) {
                        $content->setOrder($contentConfig->getOrder());
                        $result->add($content);
                    }

                }

            }

// and then sort by order
            $iterator = $result->getIterator();

// define ordering closure, using preferred comparison method/field
            $iterator->uasort(function ($first, $second) {
                return (int)$first->getOrder() > (int)$second->getOrder() ? 1 : -1;
            });

            return $iterator;


        }

    }