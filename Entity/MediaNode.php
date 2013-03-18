<?php

namespace scrclub\CMSBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MediaNode
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="scrclub\CMSBundle\Entity\MediaNodeRepository")
 */
class MediaNode
{



    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="scrclub\CMSBundle\Entity\Node")
     * @ORM\JoinColumn(name="node_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $node;

    /**
     * @ORM\ManyToOne(targetEntity="scrclub\CMSBundle\Entity\Media")
     */
    private $media;

    /**
     * @var string
     *
     * @ORM\Column(name="level", type="integer", options={"default" = 0})
     */
    private $level;

    // Getter et setter pour l'entité Article
    public function setNode(\scrclub\CMSBundle\Entity\Node $node)
    {
        $this->node = $node;
    }
    public function getNode()
    {
        return $this->node;
    }

    // Getter et setter pour l'entité Competence
    public function setMedia(\scrclub\CMSBundle\Entity\Media $media)
    {
        $this->media = $media;
    }
    public function getMedia()
    {
        return $this->media;
    }


    /**
     * Set level
     *
     * @param integer $level
     * @return MediaData
     */
    public function setLevel($level)
    {
        $this->level = $level;
    
        return $this;
    }

    /**
     * Get level
     *
     * @return integer
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * @return int
     */
    public function getId() {
        return $this->id;
    }
}