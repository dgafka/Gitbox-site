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
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="user_account_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;

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
     * @var \UserGroup
     *
     * @ORM\ManyToOne(targetEntity="UserGroup")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_group", referencedColumnName="id")
     * })
     */
    private $idGroup;

    /**
     * @var \UserDescription
     *
     * @ORM\ManyToOne(targetEntity="UserDescription")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_description", referencedColumnName="id")
     * })
     */
    private $idDescription;



    /**
     * Set login
     *
     * @param string $login
     * @return UserAccount
     */
    public function setLogin($login)
    {
        $this->login = $login;

        return $this;
    }

    /**
     * Get login
     *
     * @return string 
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return UserAccount
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string 
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set status
     *
     * @param string $status
     * @return UserAccount
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return UserAccount
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
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

    /**
     * Set idDescription
     *
     * @param \Gitbox\Bundle\CoreBundle\Entity\UserDescription $idDescription
     * @return UserAccount
     */
    public function setIdDescription(\Gitbox\Bundle\CoreBundle\Entity\UserDescription $idDescription = null)
    {
        $this->idDescription = $idDescription;

        return $this;
    }

    /**
     * Get idDescription
     *
     * @return \Gitbox\Bundle\CoreBundle\Entity\UserDescription 
     */
    public function getIdDescription()
    {
        return $this->idDescription;
    }

    /**
     * Set idGroup
     *
     * @param \Gitbox\Bundle\CoreBundle\Entity\UserGroup $idGroup
     * @return UserAccount
     */
    public function setIdGroup(\Gitbox\Bundle\CoreBundle\Entity\UserGroup $idGroup = null)
    {
        $this->idGroup = $idGroup;

        return $this;
    }

    /**
     * Get idGroup
     *
     * @return \Gitbox\Bundle\CoreBundle\Entity\UserGroup 
     */
    public function getIdGroup()
    {
        return $this->idGroup;
    }
}
