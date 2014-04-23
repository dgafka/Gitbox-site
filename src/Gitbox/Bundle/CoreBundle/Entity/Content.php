<?php

namespace Gitbox\Bundle\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Content
 *
 * @ORM\Table(name="content", indexes={@ORM\Index(name="IDX_FEC530A9F6252691", columns={"id_menu"}), @ORM\Index(name="IDX_FEC530A95697F554", columns={"id_category"})})
 * @ORM\Entity
 */
class Content
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_user", type="integer", nullable=false)
     */
    private $idUser;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=1, nullable=false)
     */
    private $status;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255, nullable=false)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="header", type="string", length=255, nullable=false)
     */
    private $header;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=false)
     */
    private $description;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="create_date", type="date", nullable=false)
     */
    private $createDate;

    /**
     * @var integer
     *
     * @ORM\Column(name="hit", type="integer", nullable=false)
     */
    private $hit;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="expire", type="date", nullable=true)
     */
    private $expire;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="last_modification_date", type="date", nullable=false)
     */
    private $lastModificationDate;

    /**
     * @var float
     *
     * @ORM\Column(name="rate", type="float", precision=10, scale=0, nullable=true)
     */
    private $rate;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=1, nullable=false)
     */
    private $type;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="content_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var \Gitbox\Bundle\CoreBundle\Entity\Category
     *
     * @ORM\ManyToOne(targetEntity="Gitbox\Bundle\CoreBundle\Entity\Category")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_category", referencedColumnName="id")
     * })
     */
    private $idCategory;

    /**
     * @var \Gitbox\Bundle\CoreBundle\Entity\Menu
     *
     * @ORM\ManyToOne(targetEntity="Gitbox\Bundle\CoreBundle\Entity\Menu")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_menu", referencedColumnName="id")
     * })
     */
    private $idMenu;

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
	 * @param \DateTime $expire
	 */
	public function setExpire($expire)
	{
		$this->expire = $expire;
	}

	/**
	 * @return \DateTime
	 */
	public function getExpire()
	{
		return $this->expire;
	}

	/**
	 * @param string $header
	 */
	public function setHeader($header)
	{
		$this->header = $header;
	}

	/**
	 * @return string
	 */
	public function getHeader()
	{
		return $this->header;
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
	 * @param \Gitbox\Bundle\CoreBundle\Entity\Category $idCategory
	 */
	public function setIdCategory($idCategory)
	{
		$this->idCategory = $idCategory;
	}

	/**
	 * @return \Gitbox\Bundle\CoreBundle\Entity\Category
	 */
	public function getIdCategory()
	{
		return $this->idCategory;
	}

	/**
	 * @param \Gitbox\Bundle\CoreBundle\Entity\Menu $idMenu
	 */
	public function setIdMenu($idMenu)
	{
		$this->idMenu = $idMenu;
	}

	/**
	 * @return \Gitbox\Bundle\CoreBundle\Entity\Menu
	 */
	public function getIdMenu()
	{
		return $this->idMenu;
	}

	/**
	 * @param int $idUser
	 */
	public function setIdUser($idUser)
	{
		$this->idUser = $idUser;
	}

	/**
	 * @return int
	 */
	public function getIdUser()
	{
		return $this->idUser;
	}

	/**
	 * @param \DateTime $lastModificationDate
	 */
	public function setLastModificationDate($lastModificationDate)
	{
		$this->lastModificationDate = $lastModificationDate;
	}

	/**
	 * @return \DateTime
	 */
	public function getLastModificationDate()
	{
		return $this->lastModificationDate;
	}

	/**
	 * @param float $rate
	 */
	public function setRate($rate)
	{
		$this->rate = $rate;
	}

	/**
	 * @return float
	 */
	public function getRate()
	{
		return $this->rate;
	}

	/**
	 * @param string $status
	 */
	public function setStatus($status)
	{
		$this->status = $status;
	}

	/**
	 * @return string
	 */
	public function getStatus()
	{
		return $this->status;
	}

	/**
	 * @param string $title
	 */
	public function setTitle($title)
	{
		$this->title = $title;
	}

	/**
	 * @return string
	 */
	public function getTitle()
	{
		return $this->title;
	}

	/**
	 * @param string $type
	 */
	public function setType($type)
	{
		$this->type = $type;
	}

	/**
	 * @return string
	 */
	public function getType()
	{
		return $this->type;
	}


}
