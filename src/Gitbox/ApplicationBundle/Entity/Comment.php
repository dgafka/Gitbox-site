<?php

namespace Gitbox\ApplicationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Comment
 *
 * @ORM\Table(name="comment", indexes={@ORM\Index(name="IDX_9474526C6B3CA4B", columns={"id_user"}), @ORM\Index(name="IDX_9474526C205899D9", columns={"id_content"})})
 * @ORM\Entity
 */
class Comment
{
    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=false)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=1, nullable=false)
     */
    private $status;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="last_modification_date", type="date", nullable=true)
     */
    private $lastModificationDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="create_date", type="date", nullable=false)
     */
    private $createDate;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="comment_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var \Gitbox\ApplicationBundle\Entity\Content
     *
     * @ORM\ManyToOne(targetEntity="Gitbox\ApplicationBundle\Entity\Content")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_content", referencedColumnName="id")
     * })
     */
    private $idContent;

    /**
     * @var \Gitbox\ApplicationBundle\Entity\UserAccount
     *
     * @ORM\ManyToOne(targetEntity="Gitbox\ApplicationBundle\Entity\UserAccount")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_user", referencedColumnName="id")
     * })
     */
    private $idUser;



    /**
     * Set description
     *
     * @param string $description
     * @return Comment
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
     * Set status
     *
     * @param string $status
     * @return Comment
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
     * Set lastModificationDate
     *
     * @param \DateTime $lastModificationDate
     * @return Comment
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
     * Set createDate
     *
     * @param \DateTime $createDate
     * @return Comment
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
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set idContent
     *
     * @param \Gitbox\ApplicationBundle\Entity\Content $idContent
     * @return Comment
     */
    public function setIdContent(\Gitbox\ApplicationBundle\Entity\Content $idContent = null)
    {
        $this->idContent = $idContent;

        return $this;
    }

    /**
     * Get idContent
     *
     * @return \Gitbox\ApplicationBundle\Entity\Content 
     */
    public function getIdContent()
    {
        return $this->idContent;
    }

    /**
     * Set idUser
     *
     * @param \Gitbox\ApplicationBundle\Entity\UserAccount $idUser
     * @return Comment
     */
    public function setIdUser(\Gitbox\ApplicationBundle\Entity\UserAccount $idUser = null)
    {
        $this->idUser = $idUser;

        return $this;
    }

    /**
     * Get idUser
     *
     * @return \Gitbox\ApplicationBundle\Entity\UserAccount 
     */
    public function getIdUser()
    {
        return $this->idUser;
    }
}
