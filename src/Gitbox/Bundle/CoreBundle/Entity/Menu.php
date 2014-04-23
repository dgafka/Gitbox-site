<?php

namespace Gitbox\Bundle\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Menu
 *
 * @ORM\Table(name="menu", indexes={@ORM\Index(name="IDX_7D053A932A1393C5", columns={"id_module"}), @ORM\Index(name="IDX_7D053A936B3CA4B", columns={"id_user"})})
 * @ORM\Entity
 */
class Menu
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_category", type="integer", nullable=true)
     */
    private $idCategory;

    /**
     * @var integer
     *
     * @ORM\Column(name="parent", type="integer", nullable=true)
     */
    private $parent;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=50, nullable=false)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=1, nullable=false)
     */
    private $status;

    /**
     * @var integer
     *
     * @ORM\Column(name="sort", type="integer", nullable=true)
     */
    private $sort;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="expire", type="date", nullable=true)
     */
    private $expire;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="menu_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var \Gitbox\Bundle\CoreBundle\Entity\UserAccount
     *
     * @ORM\ManyToOne(targetEntity="Gitbox\Bundle\CoreBundle\Entity\UserAccount")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_user", referencedColumnName="id")
     * })
     */
    private $idUser;

    /**
     * @var \Gitbox\Bundle\CoreBundle\Entity\Module
     *
     * @ORM\ManyToOne(targetEntity="Gitbox\Bundle\CoreBundle\Entity\Module")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_module", referencedColumnName="id")
     * })
     */
    private $idModule;

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
	 * @param int $idCategory
	 */
	public function setIdCategory($idCategory)
	{
		$this->idCategory = $idCategory;
	}

	/**
	 * @return int
	 */
	public function getIdCategory()
	{
		return $this->idCategory;
	}

	/**
	 * @param \Gitbox\Bundle\CoreBundle\Entity\Module $idModule
	 */
	public function setIdModule($idModule)
	{
		$this->idModule = $idModule;
	}

	/**
	 * @return \Gitbox\Bundle\CoreBundle\Entity\Module
	 */
	public function getIdModule()
	{
		return $this->idModule;
	}

	/**
	 * @param \Gitbox\Bundle\CoreBundle\Entity\UserAccount $idUser
	 */
	public function setIdUser($idUser)
	{
		$this->idUser = $idUser;
	}

	/**
	 * @return \Gitbox\Bundle\CoreBundle\Entity\UserAccount
	 */
	public function getIdUser()
	{
		return $this->idUser;
	}

	/**
	 * @param int $parent
	 */
	public function setParent($parent)
	{
		$this->parent = $parent;
	}

	/**
	 * @return int
	 */
	public function getParent()
	{
		return $this->parent;
	}

	/**
	 * @param int $sort
	 */
	public function setSort($sort)
	{
		$this->sort = $sort;
	}

	/**
	 * @return int
	 */
	public function getSort()
	{
		return $this->sort;
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


}
