<?php

    namespace scrclub\CMSBundle\Extensions;

    use Doctrine\Common\Collections\ArrayCollection;

    /**
     * Translate locales using intl extension
     * Usages:
     *  {{ getLocaleName('fr', 'es') }}
     */
    class FileExtension extends \Twig_Extension {
        /**
         * @return array
         */
        public function getFunctions() {
            return array('toMo' => new \Twig_Function_Method($this, 'toMo'),
                         'getSizeColor' => new \Twig_Function_Method($this, 'getSizeColor')
                            );
        }



        /**
         * @param $bytes
         * !!For Bootstrap only
         * Designed for getting a color string for CSS
         *
         */

        public function getSizeColor ($bytes) {

            if($bytes < 600000 )
               return "success";

            if($bytes > 600000 AND $bytes < 140000 )
                return "warning";

            if($bytes > 140000 )
                return "important";


        }

        /**
         * @param $bytes       the value in bytes
         */

        public function toMo($bytes) {
            return $this->formatBytes(intval($bytes), 0);

        }

        private function formatBytes($bytes, $precision = 2) {

            $units = array('B', 'Kb', 'Mb', 'Gb', 'Tb');

            $bytes = max($bytes, 0);
            $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
            $pow = min($pow, count($units) - 1);

            // Uncomment one of the following alternatives
             $bytes /= pow(1024, $pow);
            // $bytes /= (1 << (10 * $pow));

            return round($bytes, $precision) . ' ' . $units[$pow];
        }


        /**
         * @return string
         */
        public function getName() {
            return 'FileExtension';
        }
    }