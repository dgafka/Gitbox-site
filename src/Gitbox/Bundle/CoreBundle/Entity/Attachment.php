<?php

namespace Gitbox\Bundle\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Attachment
 *
 * @ORM\Table(name="attachment", indexes={@ORM\Index(name="IDX_795FD9BB205899D9", columns={"id_content"})})
 * @ORM\Entity
 */
class Attachment
{
    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=1, nullable=false)
     */
    private $status;

    /**
     * @var string
     *
     * @ORM\Column(name="filename", type="string", length=100, nullable=false)
     */
    private $filename;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=50, nullable=false)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="create_date", type="date", nullable=false)
     */
    private $createDate;

    /**
     * @var string
     *
     * @ORM\Column(name="mime", type="string", length=20, nullable=true)
     */
    private $mime;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="attachment_id_seq", allocationSize=1, initialValue=1)
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
	 * @param string $description
	 */
	public function setDescription($description)
	{
		$this->description = $description;
	}

	/**
	 * @return string
	 */
	public function getDescription()
	{
		return $this->description;
	}

	/**
	 * @param string $filename
	 */
	public function setFilename($filename)
	{
		$this->filename = $filename;
	}

	/**
	 * @return string
	 */
	public function getFilename()
	{
		return $this->filename;
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
	 * @param string $mime
	 */
	public function setMime($mime)
	{
		$this->mime = $mime;
	}

	/**
	 * @return string
	 */
	public function getMime()
	{
		return $this->mime;
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
