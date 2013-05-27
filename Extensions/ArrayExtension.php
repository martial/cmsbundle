<?php

    namespace scrclub\CMSBundle\Extensions;

    use Doctrine\Common\Collections\ArrayCollection;
    use Doctrine\Common\Collections\Collection;


    class ArrayExtension extends \Twig_Extension
    {
        /**
         * @return array
         */
        public function getFunctions()
        {
            return array(
                'in_array' => new \Twig_Function_Method($this, 'in_array'),
                'contains' => new \Twig_Function_Method($this, 'contains'),
                'shuffleFunc' => new \Twig_Function_Method($this, 'shuffleFunc')
            );
        }

        public function getFilters() {

            return array(

                'shuffle' => new \Twig_Filter_Method($this, 'shuffle')
            );

        }


        public function in_array($needle, $array )
        {
            return in_array($needle, (Array)$array);

        }

        
        public function shuffleFunc($array) {
	        
	        return (object)shuffle((array)$array);


        }


        public function shuffle($array) {
	        
	        
	        $array = $array->toArray();
            if ($array instanceof Traversable) {
                $array = iterator_to_array($array, false);
            }
            shuffle($array);

            return new ArrayCollection($array);

        }


        public function contains($needle, Collection $array )
        {
            return $array->contains($needle);

        }

        /**
         * @return string
         */
        public function getName()
        {
            return 'contains';
        }
    }