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

class BooleanContentType extends ContentType
{

    public function __construct()
    {
        $this->setType("boolean");

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
     * @var integer $active
     *
     * @ORM\Column(name="value", type="boolean")
     */
    protected $value;

    /**
     * @param int $value
     */
    public function setValue($value) {
        $this->value = $value;
    }

    /**
     * @return int
     */
    public function getValue() {
        return $this->value;
    }






}
