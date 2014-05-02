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
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="user_description_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="registration_date", type="date", nullable=false)
     */
    private $registrationDate = '1970-01-01';

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
    private $hit = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="token", type="string", length=32, nullable=true)
     */
    private $token;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text", nullable=true)
     */
    private $content;

    /**
     * @var integer
     *
     * @ORM\Column(name="total_rating", type="integer", nullable=false)
     */
    private $totalRating = '0';



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
     * Set token
     *
     * @param string $token
     * @return UserDescription
     */
    public function setToken($token)
    {
        $this->token = $token;

        return $this;
    }

    /**
     * Get token
     *
     * @return string 
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Set content
     *
     * @param string $content
     * @return UserDescription
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string 
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set totalRating
     *
     * @param integer $totalRating
     * @return UserDescription
     */
    public function setTotalRating($totalRating)
    {
        $this->totalRating = $totalRating;

        return $this;
    }

    /**
     * Get totalRating
     *
     * @return integer 
     */
    public function getTotalRating()
    {
        return $this->totalRating;
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
