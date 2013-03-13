<?php

namespace scrclub\CMSBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Config
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Config
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
     * @ORM\Column(name="sitename", type="string", length=255, nullable=true)
     */
    private $sitename;

    /**
     * @var string
     *
     * @ORM\Column(name="gg_email", type="string", length=255, nullable=true)
     */
    private $gg_email;

    /**
     * @var string
     *
     * @ORM\Column(name="gg_password", type="string", length=255, nullable=true)
     */
    private $gg_password;

    /**
     * @var string
     *
     * @ORM\Column(name="gg_analyticsid", type="string", length=255, nullable=true)
     */
    private $gg_analyticsid;


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
     * Set sitename
     *
     * @param string $sitename
     * @return Config
     */
    public function setSitename($sitename)
    {
        $this->sitename = $sitename;
    
        return $this;
    }

    /**
     * Get sitename
     *
     * @return string 
     */
    public function getSitename()
    {
        return $this->sitename;
    }

    /**
     * Set gg_email
     *
     * @param string $ggEmail
     * @return Config
     */
    public function setGgEmail($ggEmail)
    {
        $this->gg_email = $ggEmail;
    
        return $this;
    }

    /**
     * Get gg_email
     *
     * @return string 
     */
    public function getGgEmail()
    {
        return $this->gg_email;
    }

    /**
     * Set gg_password
     *
     * @param string $ggPassword
     * @return Config
     */
    public function setGgPassword($ggPassword)
    {
        $this->gg_password = $ggPassword;
    
        return $this;
    }

    /**
     * Get gg_password
     *
     * @return string 
     */
    public function getGgPassword()
    {
        return $this->gg_password;
    }

    /**
     * Set gg_analyticsid
     *
     * @param string $ggAnalyticsid
     * @return Config
     */
    public function setGgAnalyticsid($ggAnalyticsid)
    {
        $this->gg_analyticsid = $ggAnalyticsid;
    
        return $this;
    }

    /**
     * Get gg_analyticsid
     *
     * @return string 
     */
    public function getGgAnalyticsid()
    {
        return $this->gg_analyticsid;
    }
}