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

class DateContentType extends ContentType
{

    public function __construct()
    {
        $this->setType("date");
        $this->date = new \DateTime();

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
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $date;

    /**
     * @param int $value
     */
    public function setDate($date) {
        $this->date = $date;
    }

    /**
     * @return int
     */
    public function getDate() {
        return $this->date;
    }






}
