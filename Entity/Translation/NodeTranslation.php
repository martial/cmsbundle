<?php
    namespace scrclub\CMSBundle\Entity\Translation;

    use Doctrine\ORM\Mapping as ORM;
    use Gedmo\Translatable\Entity\MappedSuperclass\AbstractPersonalTranslation;



    /**
     * Entity\Translation\ProductTranslation.php

     * @ORM\Entity
     * @ORM\Table(name="Node_translations",
     *   uniqueConstraints={@ORM\UniqueConstraint(name="lookup_unique_idx", columns={
     *     "locale", "object_id", "field"
     *   })}
     * )
     */

    class NodeTranslation extends AbstractPersonalTranslation
    {

        /**
         * @ORM\ManyToOne(targetEntity="scrclub\CMSBundle\Entity\Node", inversedBy="translations")
         * @ORM\JoinColumn(name="object_id", referencedColumnName="id", onDelete="CASCADE")
         */
        protected $object;

    }