<?php

namespace Gitbox\Bundle\CoreBundle\Entity;

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
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="comment_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text", nullable=false)
     */
    private $content;

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
     * @var \UserAccount
     *
     * @ORM\ManyToOne(targetEntity="UserAccount")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_user", referencedColumnName="id")
     * })
     */
    private $idUser;

    /**
     * @var \Content
     *
     * @ORM\ManyToOne(targetEntity="Content")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_content", referencedColumnName="id")
     * })
     */
    private $idContent;



    /**
     * Set content
     *
     * @param string $content
     * @return Comment
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string 
     */
    public function getContent()
    {
        return $this->content;
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
     * @param \Gitbox\Bundle\CoreBundle\Entity\Content $idContent
     * @return Comment
     */
    public function setIdContent(\Gitbox\Bundle\CoreBundle\Entity\Content $idContent = null)
    {
        $this->idContent = $idContent;

        return $this;
    }

    /**
     * Get idContent
     *
     * @return \Gitbox\Bundle\CoreBundle\Entity\Content 
     */
    public function getIdContent()
    {
        return $this->idContent;
    }

    /**
     * Set idUser
     *
     * @param \Gitbox\Bundle\CoreBundle\Entity\UserAccount $idUser
     * @return Comment
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
