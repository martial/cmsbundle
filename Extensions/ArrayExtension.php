<?php

    namespace scrclub\CMSBundle\Extensions;

    use Doctrine\Common\Collections\ArrayCollection;
    use Doctrine\Common\Collections\Collection;

    /**
     * Translate locales using intl extension
     * Usages:
     *  {{ getLocaleName('fr', 'es') }}
     */
    class ArrayExtension extends \Twig_Extension
    {
        /**
         * @return array
         */
        public function getFunctions()
        {
            return array(
                'in_array' => new \Twig_Function_Method($this, 'in_array'),
                'contains' => new \Twig_Function_Method($this, 'contains')
            );
        }

        /**
         * @param $locale       the locale you need to translate
         * @param $locale_out   in which lang locale you need
         */
        public function in_array($needle, $array )
        {
            return in_array($needle, (Array)$array);

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