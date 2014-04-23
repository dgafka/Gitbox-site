<?php

namespace Gitbox\Bundle\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * BannedIp
 *
 * @ORM\Table(name="banned_ip")
 * @ORM\Entity
 */
class BannedIp
{
    /**
     * @var string
     *
     * @ORM\Column(name="ip", type="string", length=15, nullable=false)
     */
    private $ip;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="create_date", type="date", nullable=false)
     */
    private $createDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="expire_date", type="date", nullable=false)
     */
    private $expireDate;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=false)
     */
    private $description;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="banned_ip_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;

	/**
	 * @param \DateTime $createDate
	 */
	public function setCreateDate($createDate)
	{
		$this->createDate = $createDate;
	}

	/**
	 * @return \DateTime
	 */
	public function getCreateDate()
	{
		return $this->createDate;
	}

	/**
	 * @param string $description
	 */
	public function setDescription($description)
	{
		$this->description = $description;
	}

	/**
	 * @return string
	 */
	public function getDescription()
	{
		return $this->description;
	}

	/**
	 * @param \DateTime $expireDate
	 */
	public function setExpireDate($expireDate)
	{
		$this->expireDate = $expireDate;
	}

	/**
	 * @return \DateTime
	 */
	public function getExpireDate()
	{
		return $this->expireDate;
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


}
