<?php

namespace Gitbox\Bundle\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UserModules
 *
 * @ORM\Table(name="user_modules", indexes={@ORM\Index(name="IDX_76BA5DDD6B3CA4B", columns={"id_user"}), @ORM\Index(name="IDX_76BA5DDD2A1393C5", columns={"id_module"})})
 * @ORM\Entity
 */
class UserModules
{
    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=1, nullable=false)
     */
    private $status;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="user_modules_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;

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
     * @var \Gitbox\Bundle\CoreBundle\Entity\UserAccount
     *
     * @ORM\ManyToOne(targetEntity="Gitbox\Bundle\CoreBundle\Entity\UserAccount")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_user", referencedColumnName="id")
     * })
     */
    private $idUser;

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


}
