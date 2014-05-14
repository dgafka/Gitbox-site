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
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="menu_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;

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
     * @var \Module
     *
     * @ORM\ManyToOne(targetEntity="Module")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_module", referencedColumnName="id")
     * })
     */
    private $idModule;

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
     * Set parent
     *
     * @param integer $parent
     * @return Menu
     */
    public function setParent($parent)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return integer 
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Menu
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set sort
     *
     * @param integer $sort
     * @return Menu
     */
    public function setSort($sort)
    {
        $this->sort = $sort;

        return $this;
    }

    /**
     * Get sort
     *
     * @return integer 
     */
    public function getSort()
    {
        return $this->sort;
    }

    /**
     * Set expire
     *
     * @param \DateTime $expire
     * @return Menu
     */
    public function setExpire($expire)
    {
        $this->expire = $expire;

        return $this;
    }

    /**
     * Get expire
     *
     * @return \DateTime 
     */
    public function getExpire()
    {
        return $this->expire;
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
     * Set idUser
     *
     * @param \Gitbox\Bundle\CoreBundle\Entity\UserAccount $idUser
     * @return Menu
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

    /**
     * Set idModule
     *
     * @param \Gitbox\Bundle\CoreBundle\Entity\Module $idModule
     * @return Menu
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
}
