<?php

namespace Gitbox\Bundle\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Category
 *
 * @ORM\Table(name="category")
 * @ORM\Entity
 */
class Category
{
    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=50, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="category_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Gitbox\Bundle\CoreBundle\Entity\Content", mappedBy="idCategory")
     */
    private $idContent;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->idContent = new \Doctrine\Common\Collections\ArrayCollection();
    }


    /**
     * Set name
     *
     * @param string $name
     * @return Category
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Category
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
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Add idContent
     *
     * @param \Gitbox\Bundle\CoreBundle\Entity\Content $idContent
     * @return Category
     */
    public function addIdContent(\Gitbox\Bundle\CoreBundle\Entity\Content $idContent)
    {
        $this->idContent[] = $idContent;

        return $this;
    }

    /**
     * Remove idContent
     *
     * @param \Gitbox\Bundle\CoreBundle\Entity\Content $idContent
     */
    public function removeIdContent(\Gitbox\Bundle\CoreBundle\Entity\Content $idContent)
    {
        $this->idContent->removeElement($idContent);
    }

    /**
     * Get idContent
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getIdContent()
    {
        return $this->idContent;
    }
}
