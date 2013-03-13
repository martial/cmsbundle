<?php

namespace scrclub\CMSBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use scrclub\CMSBundle\Entity\Node;

/**
 * @ORM\Table()
 * @ORM\Entity
 */
class Post extends Node
{


    public function __toString() {

        return "post";

    }

}
