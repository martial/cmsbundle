<?php

    namespace scrclub\CMSBundle\Extensions;

    use Symfony\Component\Locale\Locale;

    /**
     * Translate locales using intl extension
     * Usages:
     *  {{ getLocaleName('fr', 'es') }}
     */
    class LocaleExtension extends \Twig_Extension
    {
        /**
         * @return array
         */
        public function getFunctions()
        {
            return array(
                'getLocaleName' => new \Twig_Function_Method($this, 'getLocaleName')
            );
        }

        /**
         * @param $locale       the locale you need to translate
         * @param $locale_out   in which lang locale you need
         */
        public function getLocaleName($locale, $locale_out )
        {

            $languages = Locale::getDisplayLanguages($locale_out);
            return $languages[$locale];

        }

        /**
         * @return string
         */
        public function getName()
        {
            return 'getLocaleName';
        }
    }