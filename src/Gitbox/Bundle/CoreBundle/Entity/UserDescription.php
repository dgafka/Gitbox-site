<?php

namespace Gitbox\Bundle\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UserDescription
 *
 * @ORM\Table(name="user_description")
 * @ORM\Entity
 */
class UserDescription
{
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="registration_date", type="date", nullable=false)
     */
    private $registrationDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="ban_date", type="date", nullable=true)
     */
    private $banDate;

    /**
     * @var string
     *
     * @ORM\Column(name="ip", type="string", length=15, nullable=true)
     */
    private $ip;

    /**
     * @var integer
     *
     * @ORM\Column(name="hit", type="integer", nullable=false)
     */
    private $hit;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="user_description_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;



    /**
     * Set registrationDate
     *
     * @param \DateTime $registrationDate
     * @return UserDescription
     */
    public function setRegistrationDate($registrationDate)
    {
        $this->registrationDate = $registrationDate;

        return $this;
    }

    /**
     * Get registrationDate
     *
     * @return \DateTime 
     */
    public function getRegistrationDate()
    {
        return $this->registrationDate;
    }

    /**
     * Set banDate
     *
     * @param \DateTime $banDate
     * @return UserDescription
     */
    public function setBanDate($banDate)
    {
        $this->banDate = $banDate;

        return $this;
    }

    /**
     * Get banDate
     *
     * @return \DateTime 
     */
    public function getBanDate()
    {
        return $this->banDate;
    }

    /**
     * Set ip
     *
     * @param string $ip
     * @return UserDescription
     */
    public function setIp($ip)
    {
        $this->ip = $ip;

        return $this;
    }

    /**
     * Get ip
     *
     * @return string 
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * Set hit
     *
     * @param integer $hit
     * @return UserDescription
     */
    public function setHit($hit)
    {
        $this->hit = $hit;

        return $this;
    }

    /**
     * Get hit
     *
     * @return integer 
     */
    public function getHit()
    {
        return $this->hit;
    }

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
