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
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="user_modules_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=1, nullable=false)
     */
    private $status = 'D';

    /**
     * @var integer
     *
     * @ORM\Column(name="total_contents", type="integer", nullable=false)
     */
    private $totalContents = '0';

    /**
     * @var \UserAccount
     *
     * @ORM\ManyToOne(targetEntity="UserAccount")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_user", referencedColumnName="id")
     * })
     */
    private $idUser;

    /**
     * @var \Module
     *
     * @ORM\ManyToOne(targetEntity="Module")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_module", referencedColumnName="id")
     * })
     */
    private $idModule;



    /**
     * Set status
     *
     * @param string $status
     * @return UserModules
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
     * Set totalContents
     *
     * @param integer $totalContents
     * @return UserModules
     */
    public function setTotalContents($totalContents)
    {
        $this->totalContents = $totalContents;

        return $this;
    }

    /**
     * Get totalContents
     *
     * @return integer 
     */
    public function getTotalContents()
    {
        return $this->totalContents;
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
     * Set idModule
     *
     * @param \Gitbox\Bundle\CoreBundle\Entity\Module $idModule
     * @return UserModules
     */
    public function setIdModule(\Gitbox\Bundle\CoreBundle\Entity\Module $idModule = null)
    {
        $this->idModule = $idModule;

        return $this;
    }

    /**
     * Get idModule
     *
     * @return \Gitbox\Bundle\CoreBundle\Entity\Module 
     */
    public function getIdModule()
    {
        return $this->idModule;
    }

    /**
     * Set idUser
     *
     * @param \Gitbox\Bundle\CoreBundle\Entity\UserAccount $idUser
     * @return UserModules
     */
    public function setIdUser(\Gitbox\Bundle\CoreBundle\Entity\UserAccount $idUser = null)
    {
        $this->idUser = $idUser;

        return $this;
    }

    /**
     * Get idUser
     *
     * @return \Gitbox\Bundle\CoreBundle\Entity\UserAccount 
     */
    public function getIdUser()
    {
        return $this->idUser;
    }
}
