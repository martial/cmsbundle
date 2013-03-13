<?php

    namespace scrclub\CMSBundle\Extensions;

    /**
     * Inspect Twig templates with a debugger.
     * Usages:
     *  {{ inspect() }}
     *  {{ inspect(myVar) }}
     */
    class InspectExtension extends \Twig_Extension
    {
        /**
         * @return array
         */
        public function getFunctions()
        {
            return array(
                'inspect' => new \Twig_Function_Method($this, 'inspect')
            );
        }

        /**
         * @param $context
         */
        public function inspect($var)
        {
            echo var_dump($var);
            return; // breakpoint
        }

        /**
         * This where you set your breakpoint
         */
        protected function breakPoint($twig)
        {

            echo var_dump(func_get_arg(0));
            return; // breakpoint
        }

        /**
         * @return string
         */
        public function getName()
        {
            return 'inspect';
        }
    }