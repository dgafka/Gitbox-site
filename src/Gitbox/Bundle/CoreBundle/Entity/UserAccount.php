<?php

namespace Gitbox\Bundle\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UserAccount
 *
 * @ORM\Table(name="user_account", uniqueConstraints={@ORM\UniqueConstraint(name="user_unique_login", columns={"login"}), @ORM\UniqueConstraint(name="email_unique", columns={"email"})}, indexes={@ORM\Index(name="IDX_253B48AE834505F5", columns={"id_group"}), @ORM\Index(name="IDX_253B48AE75F68DD1", columns={"id_description"})})
 * @ORM\Entity
 */
class UserAccount
{
    /**
     * @var string
     *
     * @ORM\Column(name="login", type="string", length=25, nullable=false)
     */
    private $login;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=32, nullable=false)
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=1, nullable=false)
     */
    private $status;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=50, nullable=false)
     */
    private $email;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="user_account_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var \Gitbox\Bundle\CoreBundle\Entity\UserGroup
     *
     * @ORM\ManyToOne(targetEntity="Gitbox\Bundle\CoreBundle\Entity\UserGroup")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_group", referencedColumnName="id")
     * })
     */
    private $idGroup;

    /**
     * @var \Gitbox\Bundle\CoreBundle\Entity\UserDescription
     *
     * @ORM\ManyToOne(targetEntity="Gitbox\Bundle\CoreBundle\Entity\UserDescription")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_description", referencedColumnName="id")
     * })
     */
    private $idDescription;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Gitbox\Bundle\CoreBundle\Entity\Module", inversedBy="idUser")
     * @ORM\JoinTable(name="user_modules",
     *   joinColumns={
     *     @ORM\JoinColumn(name="id_user", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="id_module", referencedColumnName="id")
     *   }
     * )
     */
    private $idModule;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->idModule = new \Doctrine\Common\Collections\ArrayCollection();
    }

	/**
	 * @param string $email
	 */
	public function setEmail($email)
	{
		$this->email = $email;
	}

	/**
	 * @return string
	 */
	public function getEmail()
	{
		return $this->email;
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
	 * @param \Gitbox\Bundle\CoreBundle\Entity\UserDescription $idDescription
	 */
	public function setIdDescription($idDescription)
	{
		$this->idDescription = $idDescription;
	}

	/**
	 * @return \Gitbox\Bundle\CoreBundle\Entity\UserDescription
	 */
	public function getIdDescription()
	{
		return $this->idDescription;
	}

	/**
	 * @param \Gitbox\Bundle\CoreBundle\Entity\UserGroup $idGroup
	 */
	public function setIdGroup($idGroup)
	{
		$this->idGroup = $idGroup;
	}

	/**
	 * @return \Gitbox\Bundle\CoreBundle\Entity\UserGroup
	 */
	public function getIdGroup()
	{
		return $this->idGroup;
	}

	/**
	 * @param \Doctrine\Common\Collections\Collection $idModule
	 */
	public function setIdModule($idModule)
	{
		$this->idModule = $idModule;
	}

	/**
	 * @return \Doctrine\Common\Collections\Collection
	 */
	public function getIdModule()
	{
		return $this->idModule;
	}

	/**
	 * @param string $login
	 */
	public function setLogin($login)
	{
		$this->login = $login;
	}

	/**
	 * @return string
	 */
	public function getLogin()
	{
		return $this->login;
	}

	/**
	 * @param string $password
	 */
	public function setPassword($password)
	{
		$this->password = $password;
	}

	/**
	 * @return string
	 */
	public function getPassword()
	{
		return $this->password;
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
