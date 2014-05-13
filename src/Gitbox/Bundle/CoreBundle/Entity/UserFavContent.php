<?php

namespace Gitbox\Bundle\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UserFavContent
 *
 * @ORM\Table(name="user_fav_content", indexes={@ORM\Index(name="IDX_28CEFFAE6B3CA4B", columns={"id_user"}), @ORM\Index(name="IDX_28CEFFAE205899D9", columns={"id_content"})})
 * @ORM\Entity
 */
class UserFavContent
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="user_fav_content_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;

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
     * @return UserFavContent
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
     * @return UserFavContent
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
