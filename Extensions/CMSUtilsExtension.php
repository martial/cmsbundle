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
                'linkText' => new \Twig_Function_Method($this, 'linkText'),
                'getTextContent' => new \Twig_Function_Method($this, 'getTextContent'),
                'getCategories' => new \Twig_Function_Method($this, 'getCategories'),

            );
        }





        public function getTextContent(Node $node, $type) {

            foreach($node->getTextContent() as $text ) {

                if($text->getType() == $type)
                    return $text->getText();

            }

            return "";

        }

        public function getCategories ($nodes) {

            $result = new ArrayCollection();

            foreach ($nodes as $node) {

                $categories = $node->getCategories();

                foreach ($categories as $category) {

                    if( !$result->contains($category)) {
                        $result->add($category);
                    }
                }

            }

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