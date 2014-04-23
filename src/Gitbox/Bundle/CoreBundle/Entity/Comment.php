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
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="comment_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var \Gitbox\Bundle\CoreBundle\Entity\Content
     *
     * @ORM\ManyToOne(targetEntity="Gitbox\Bundle\CoreBundle\Entity\Content")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_content", referencedColumnName="id")
     * })
     */
    private $idContent;

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
	 * @param string $content
	 */
	public function setContent($content)
	{
		$this->content = $content;
	}

	/**
	 * @return string
	 */
	public function getContent()
	{
		return $this->content;
	}

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
	 * @param \Gitbox\Bundle\CoreBundle\Entity\Content $idContent
	 */
	public function setIdContent($idContent)
	{
		$this->idContent = $idContent;
	}

	/**
	 * @return \Gitbox\Bundle\CoreBundle\Entity\Content
	 */
	public function getIdContent()
	{
		return $this->idContent;
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
