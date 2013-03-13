<?php

namespace scrclub\CMSBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Locale\Locale;

/**
 * langsRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class LangsRepository extends EntityRepository
{

        public function getLocales ($all = NULL) {

            if ( !isset($all)) {

                $all = $this->findAll();

            }

            $locales = array();
            foreach ($all as $l)
                array_push($locales, $l->getLocale());


            return $locales;


        }

}