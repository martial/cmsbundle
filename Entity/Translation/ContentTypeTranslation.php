<?php
    namespace scrclub\CMSBundle\Entity\Translation;

    use Doctrine\ORM\Mapping as ORM;
    use Gedmo\Translatable\Entity\MappedSuperclass\AbstractPersonalTranslation;



    /**
     *

     * @ORM\Entity
     * @ORM\Table(name="ContentType_translations",
     *   uniqueConstraints={@ORM\UniqueConstraint(name="lookup_unique_idx", columns={
     *     "locale", "object_id", "field"
     *   })}
     * )
     */

    class ContentTypeTranslation extends AbstractPersonalTranslation
    {

        /**
         * @ORM\ManyToOne(targetEntity="scrclub\CMSBundle\Entity\ContentType", inversedBy="translations")
         * @ORM\JoinColumn(name="object_id", referencedColumnName="id", onDelete="CASCADE")
         */
        protected $object;

    }