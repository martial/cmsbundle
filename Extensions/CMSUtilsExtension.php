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
                'getTextContent' => new \Twig_Function_Method($this, 'getTextContent'),

            );
        }

        public function getTextContent(Node $node, $type) {

            foreach($node->getTextContent() as $text ) {

                if($text->getType() == $type)
                    return $text->getText();

            }

            return "";

        }


        /**
         * @return string
         */
        public function getName()
        {
            return 'contains';
        }
    }