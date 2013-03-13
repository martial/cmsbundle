<?php

namespace scrclub\CMSBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Locale\Locale;

/**
 * langs
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="scrclub\CMSBundle\Entity\LangsRepository")
 */
class Langs
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="locale", type="string", length=4)
     */
    private $locale;



    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set locale
     *
     * @param string $locale
     * @return langs
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;
    
        return $this;
    }

    /**
     * Get locale
     *
     * @return string 
     */
    public function getLocale()
    {
        return $this->locale;
    }

    // var to handle translation via intl
    public function getNameTranslated($locale)
    {
        $languages = Locale::getDisplayLanguages($locale);
        return $languages[$this->getLocale()];
    }


}