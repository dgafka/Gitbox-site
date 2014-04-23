<?php

namespace Gitbox\Bundle\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ContentShare
 *
 * @ORM\Table(name="content_share", indexes={@ORM\Index(name="IDX_4C5C9886B3CA4B", columns={"id_user"}), @ORM\Index(name="IDX_4C5C988205899D9", columns={"id_content"})})
 * @ORM\Entity
 */
class ContentShare
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="content_share_id_seq", allocationSize=1, initialValue=1)
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


}
