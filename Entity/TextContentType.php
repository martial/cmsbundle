<?php

namespace scrclub\CMSBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * Category
 *
 * @ORM\Table()
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="scrclub\CMSBundle\Entity\ContentTypeRepository")
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 */

class TextContentType extends ContentType
{

    public function __construct()
    {
        $this->setType("text");
        $this->text = "";

    }

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;


    /**
     * @var string
     * @Gedmo\Translatable
     * @ORM\Column(name="text", type="text", nullable=false)
     */

    private $text = "";

    /**
     * @Gedmo\Locale
     * Used locale to override Translation listener`s locale
     * this is not a mapped field of entity metadata, just a simple property
     */
    protected $locale;


    /**
     * @param string $text
     */
    public function setText($text) {

        if($text == null) {
            $this->text = " ";
        } else {
            $this->text = $text;
        }


    }

    /**
     * @return string
     */
    public function getText() {
        return $this->text;
    }



    public function setTranslatableLocale($locale)
    {
        $this->locale = $locale;
    }





}
