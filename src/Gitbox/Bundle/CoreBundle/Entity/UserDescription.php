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
     * @var string
     *
     * @ORM\Column(name="token", type="string", length=32, nullable=true)
     */
    private $token;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="content", type="string", nullable=true)
	 */
	private $content;

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
	 * @param \DateTime $banDate
	 */
	public function setBanDate($banDate)
	{
		$this->banDate = $banDate;
	}

	/**
	 * @return \DateTime
	 */
	public function getBanDate()
	{
		return $this->banDate;
	}

	/**
	 * @param int $hit
	 */
	public function setHit($hit)
	{
		$this->hit = $hit;
	}

	/**
	 * @return int
	 */
	public function getHit()
	{
		return $this->hit;
	}

	/**
	 * @param int $id
	 */
	public function setId($id)
	{
		$this->id = $id;
	}

	/**
	 * @return int
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * @param string $ip
	 */
	public function setIp($ip)
	{
		$this->ip = $ip;
	}

	/**
	 * @return string
	 */
	public function getIp()
	{
		return $this->ip;
	}

	/**
	 * @param \DateTime $registrationDate
	 */
	public function setRegistrationDate($registrationDate)
	{
		$this->registrationDate = $registrationDate;
	}

	/**
	 * @return \DateTime
	 */
	public function getRegistrationDate()
	{
		return $this->registrationDate;
	}

	/**
	 * @param string $token
	 */
	public function setToken($token)
	{
		$this->token = $token;
	}

	/**
	 * @return string
	 */
	public function getToken()
	{
		return $this->token;
	}

	/**
	 * @param string $content
	 */
	public function setContent($content)
	{
		$this->content = $content;
	}

	/**
	 * @return string
	 */
	public function getContent()
	{
		return $this->content;
	}


}
