<?php

namespace scrclub\CMSBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Locale\Locale;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * langs
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="scrclub\CMSBundle\Entity\LangsRepository")
 * @Gedmo\TranslationEntity(class="scrclub\CMSBundle\Entity\Translation\NodeTranslation")
 */
class Date
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
     * @Gedmo\Translatable
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @param string $dateEnd
     */
    public function setDateEnd($dateEnd) {
        $this->dateEnd = $dateEnd;
    }

    /**
     * @return string
     */
    public function getDateEnd() {
        return $this->dateEnd;
    }

    /**
     * @param string $dateStart
     */
    public function setDateStart($dateStart) {
        $this->dateStart = $dateStart;
    }

    /**
     * @return string
     */
    public function getDateStart() {
        return $this->dateStart;
    }

    /**
     * @param string $description
     */
    public function setDescription($description) {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getDescription() {
        return $this->description;
    }

    /**
     * @var string
     *
     *  @ORM\Column(type="datetime")
     */
    private $dateStart;



    /**
     * @var string
     *
     * @ORM\Column(type="datetime")
     */
    private $dateEnd;




    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }




}