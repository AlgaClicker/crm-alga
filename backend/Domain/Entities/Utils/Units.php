<?php

namespace Domain\Entities\Utils;

use Doctrine\ORM\Mapping as ORM;

/**
 * Units
 *
 * @ORM\Table(name="units")
 * @ORM\Entity
 */
class Units
{
    /**
     * @var uuid
     *
     * @ORM\Column(name="id", type="uuid", options={"unsigned"=true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="CUSTOM")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string", nullable=false, unique=true)
     */
    private $code;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", nullable=false, unique=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", nullable=false)
     */
    private $title;

    /**
     * @var float
     *
     * @ORM\Column(name="factor", type="float", nullable=false)
     */
    private $factor;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    private $createdAt;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @var uuid|null
     *
     * @ORM\Column(name="autor_uuid", type="uuid", nullable=true)
     */
    private $autorId;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="active", type="boolean", nullable=true)
     */
    private $active;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="delete", type="boolean", nullable=true)
     */
    private $delete = false;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Domain\Entities\Business\Directory\Material", mappedBy="unit")
     */
    private $material;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Domain\Entities\Business\Objects\SpecificationMaterial", mappedBy="unit")
     */
    private $specificationMaterial;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Domain\Entities\Business\Objects\SpecificationTypeWorks", mappedBy="unit")
     */
    private $specificationTypeworks;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->material = new \Doctrine\Common\Collections\ArrayCollection();
        $this->specificationMaterial = new \Doctrine\Common\Collections\ArrayCollection();
        $this->specificationTypeworks = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id.
     *
     * @return uuid
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set code.
     *
     * @param string $code
     *
     * @return Units
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code.
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set name.
     *
     * @param string $name
     *
     * @return Units
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set title.
     *
     * @param string $title
     *
     * @return Units
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set factor.
     *
     * @param float $factor
     *
     * @return Units
     */
    public function setFactor($factor)
    {
        $this->factor = $factor;

        return $this;
    }

    /**
     * Get factor.
     *
     * @return float
     */
    public function getFactor()
    {
        return $this->factor;
    }

    /**
     * Set createdAt.
     *
     * @param \DateTime $createdAt
     *
     * @return Units
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt.
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt.
     *
     * @param \DateTime|null $updatedAt
     *
     * @return Units
     */
    public function setUpdatedAt($updatedAt = null)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt.
     *
     * @return \DateTime|null
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set autorId.
     *
     * @param uuid|null $autorId
     *
     * @return Units
     */
    public function setAutorId($autorId = null)
    {
        $this->autorId = $autorId;

        return $this;
    }

    /**
     * Get autorId.
     *
     * @return uuid|null
     */
    public function getAutorId()
    {
        return $this->autorId;
    }

    /**
     * Set active.
     *
     * @param bool|null $active
     *
     * @return Units
     */
    public function setActive($active = null)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get active.
     *
     * @return bool|null
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * Set delete.
     *
     * @param bool|null $delete
     *
     * @return Units
     */
    public function setDelete($delete = null)
    {
        $this->delete = $delete;

        return $this;
    }

    /**
     * Get delete.
     *
     * @return bool|null
     */
    public function getDelete()
    {
        return $this->delete;
    }

    /**
     * Add material.
     *
     * @param \Domain\Entities\Business\Directory\Material $material
     *
     * @return Units
     */
    public function addMaterial(\Domain\Entities\Business\Directory\Material $material)
    {
        $this->material[] = $material;

        return $this;
    }

    /**
     * Remove material.
     *
     * @param \Domain\Entities\Business\Directory\Material $material
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeMaterial(\Domain\Entities\Business\Directory\Material $material)
    {
        return $this->material->removeElement($material);
    }

    /**
     * Get material.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMaterial()
    {
        return $this->material;
    }

    /**
     * Add specificationMaterial.
     *
     * @param \Domain\Entities\Business\Objects\SpecificationMaterial $specificationMaterial
     *
     * @return Units
     */
    public function addSpecificationMaterial(\Domain\Entities\Business\Objects\SpecificationMaterial $specificationMaterial)
    {
        $this->specificationMaterial[] = $specificationMaterial;

        return $this;
    }

    /**
     * Remove specificationMaterial.
     *
     * @param \Domain\Entities\Business\Objects\SpecificationMaterial $specificationMaterial
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeSpecificationMaterial(\Domain\Entities\Business\Objects\SpecificationMaterial $specificationMaterial)
    {
        return $this->specificationMaterial->removeElement($specificationMaterial);
    }

    /**
     * Get specificationMaterial.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSpecificationMaterial()
    {
        return $this->specificationMaterial;
    }

    /**
     * Add specificationTypework.
     *
     * @param \Domain\Entities\Business\Objects\SpecificationTypeWorks $specificationTypework
     *
     * @return Units
     */
    public function addSpecificationTypework(\Domain\Entities\Business\Objects\SpecificationTypeWorks $specificationTypework)
    {
        $this->specificationTypeworks[] = $specificationTypework;

        return $this;
    }

    /**
     * Remove specificationTypework.
     *
     * @param \Domain\Entities\Business\Objects\SpecificationTypeWorks $specificationTypework
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeSpecificationTypework(\Domain\Entities\Business\Objects\SpecificationTypeWorks $specificationTypework)
    {
        return $this->specificationTypeworks->removeElement($specificationTypework);
    }

    /**
     * Get specificationTypeworks.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSpecificationTypeworks()
    {
        return $this->specificationTypeworks;
    }
}
