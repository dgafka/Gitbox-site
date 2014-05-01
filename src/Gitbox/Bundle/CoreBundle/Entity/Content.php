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
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="content_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;

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
    private $status = 'A';

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255, nullable=false)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="header", type="string", length=255, nullable=true)
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
     * @ORM\Column(name="create_date", type="datetime", nullable=false)
     */
    private $createDate = '1970-01-01';

    /**
     * @var integer
     *
     * @ORM\Column(name="hit", type="integer", nullable=false)
     */
    private $hit = '0';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="expire", type="date", nullable=true)
     */
    private $expire;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="last_modification_date", type="datetime", nullable=false)
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
    private $type = '1';

    /**
     * @var \Menu
     *
     * @ORM\ManyToOne(targetEntity="Menu")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_menu", referencedColumnName="id")
     * })
     */
    private $idMenu;

    /**
     * @var \Category
     *
     * @ORM\ManyToOne(targetEntity="Category")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_category", referencedColumnName="id")
     * })
     */
    private $idCategory;



    /**
     * Set idUser
     *
     * @param integer $idUser
     * @return Content
     */
    public function setIdUser($idUser)
    {
        $this->idUser = $idUser;

        return $this;
    }

    /**
     * Get idUser
     *
     * @return integer 
     */
    public function getIdUser()
    {
        return $this->idUser;
    }

    /**
     * Set status
     *
     * @param string $status
     * @return Content
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
     * Set title
     *
     * @param string $title
     * @return Content
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
     * Set header
     *
     * @param string $header
     * @return Content
     */
    public function setHeader($header)
    {
        $this->header = $header;

        return $this;
    }

    /**
     * Get header
     *
     * @return string 
     */
    public function getHeader()
    {
        return $this->header;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Content
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set createDate
     *
     * @param \DateTime $createDate
     * @return Content
     */
    public function setCreateDate($createDate)
    {
        $this->createDate = $createDate;

        return $this;
    }

    /**
     * Get createDate
     *
     * @return \DateTime 
     */
    public function getCreateDate()
    {
        return $this->createDate;
    }

    /**
     * Set hit
     *
     * @param integer $hit
     * @return Content
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
     * Set expire
     *
     * @param \DateTime $expire
     * @return Content
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
     * Set lastModificationDate
     *
     * @param \DateTime $lastModificationDate
     * @return Content
     */
    public function setLastModificationDate($lastModificationDate)
    {
        $this->lastModificationDate = $lastModificationDate;

        return $this;
    }

    /**
     * Get lastModificationDate
     *
     * @return \DateTime 
     */
    public function getLastModificationDate()
    {
        return $this->lastModificationDate;
    }

    /**
     * Set rate
     *
     * @param float $rate
     * @return Content
     */
    public function setRate($rate)
    {
        $this->rate = $rate;

        return $this;
    }

    /**
     * Get rate
     *
     * @return float 
     */
    public function getRate()
    {
        return $this->rate;
    }

    /**
     * Set type
     *
     * @param string $type
     * @return Content
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
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
     * Set idCategory
     *
     * @param \Gitbox\Bundle\CoreBundle\Entity\Category $idCategory
     * @return Content
     */
    public function setIdCategory(\Gitbox\Bundle\CoreBundle\Entity\Category $idCategory = null)
    {
        $this->idCategory = $idCategory;

        return $this;
    }

    /**
     * Get idCategory
     *
     * @return \Gitbox\Bundle\CoreBundle\Entity\Category 
     */
    public function getIdCategory()
    {
        return $this->idCategory;
    }

    /**
     * Set idMenu
     *
     * @param \Gitbox\Bundle\CoreBundle\Entity\Menu $idMenu
     * @return Content
     */
    public function setIdMenu(\Gitbox\Bundle\CoreBundle\Entity\Menu $idMenu = null)
    {
        $this->idMenu = $idMenu;

        return $this;
    }

    /**
     * Get idMenu
     *
     * @return \Gitbox\Bundle\CoreBundle\Entity\Menu 
     */
    public function getIdMenu()
    {
        return $this->idMenu;
    }
}
