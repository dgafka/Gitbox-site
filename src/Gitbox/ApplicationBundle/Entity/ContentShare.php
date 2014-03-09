<?php

namespace Gitbox\ApplicationBundle\Entity;

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
     * @return ContentShare
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
     * @return ContentShare
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
