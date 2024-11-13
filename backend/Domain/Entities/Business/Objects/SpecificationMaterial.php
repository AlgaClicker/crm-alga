<?php

namespace Domain\Entities\Business\Objects;

use Doctrine\ORM\Mapping as ORM;

/**
 * SpecificationMaterial
 *
 * @ORM\Table(name="specification_material")
 * @ORM\Entity
 */
class SpecificationMaterial
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
     * @var int|null
     *
     * @ORM\Column(name="index", type="integer", nullable=true)
     */
    private $index;

    /**
     * @var string|null
     *
     * @ORM\Column(name="position", type="text", nullable=true)
     */
    private $position;

    /**
     * @var string|null
     *
     * @ORM\Column(name="rationale", type="string", nullable=true)
     */
    private $rationale;

    /**
     * @var string|null
     *
     * @ORM\Column(name="fullname", type="string", nullable=true)
     */
    private $fullname;

    /**
     * @var string|null
     *
     * @ORM\Column(name="description", type="string", nullable=true)
     */
    private $description;

    /**
     * @var string|null
     *
     * @ORM\Column(name="type", type="string", nullable=true)
     */
    private $type;

    /**
     * @var string|null
     *
     * @ORM\Column(name="code", type="string", nullable=true)
     */
    private $code;

    /**
     * @var string|null
     *
     * @ORM\Column(name="vendor", type="string", nullable=true)
     */
    private $vendor;

    /**
     * @var float|null
     *
     * @ORM\Column(name="quantity", type="float", nullable=true)
     */
    private $quantity;

    /**
     * @var float|null
     *
     * @ORM\Column(name="mass_unit", type="float", nullable=true)
     */
    private $massUnit;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="is_group", type="boolean", nullable=true)
     */
    private $isGroup;

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
     * @ORM\Column(name="draft", type="boolean", nullable=true)
     */
    private $draft;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="delete", type="boolean", nullable=true)
     */
    private $delete = false;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="deleted_at", type="datetime", nullable=true)
     */
    private $deletedAt;

    /**
     * @var \Domain\Entities\Business\Objects\SpecificationMaterial
     *
     * @ORM\ManyToOne(targetEntity="Domain\Entities\Business\Objects\SpecificationMaterial")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="parent", referencedColumnName="id")
     * })
     */
    private $parent;

    /**
     * @var \Domain\Entities\Utils\Units
     *
     * @ORM\ManyToOne(targetEntity="Domain\Entities\Utils\Units", inversedBy="specificationMaterial")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="unit_id", referencedColumnName="id")
     * })
     */
    private $unit;

    /**
     * @var \Domain\Entities\Business\Directory\Material
     *
     * @ORM\ManyToOne(targetEntity="Domain\Entities\Business\Directory\Material", inversedBy="specificationMaterial")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="material_id", referencedColumnName="id")
     * })
     */
    private $material;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Domain\Entities\Business\Objects\Specification", inversedBy="specificationMaterials")
     * @ORM\JoinTable(name="specification_specification_material",
     *   joinColumns={
     *     @ORM\JoinColumn(name="specification_material_id", referencedColumnName="id", onDelete="CASCADE")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="specification_id", referencedColumnName="id", onDelete="CASCADE")
     *   }
     * )
     */
    private $specification = array();

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->specification = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set index.
     *
     * @param int|null $index
     *
     * @return SpecificationMaterial
     */
    public function setIndex($index = null)
    {
        $this->index = $index;

        return $this;
    }

    /**
     * Get index.
     *
     * @return int|null
     */
    public function getIndex()
    {
        return $this->index;
    }

    /**
     * Set position.
     *
     * @param string|null $position
     *
     * @return SpecificationMaterial
     */
    public function setPosition($position = null)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Get position.
     *
     * @return string|null
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Set rationale.
     *
     * @param string|null $rationale
     *
     * @return SpecificationMaterial
     */
    public function setRationale($rationale = null)
    {
        $this->rationale = $rationale;

        return $this;
    }

    /**
     * Get rationale.
     *
     * @return string|null
     */
    public function getRationale()
    {
        return $this->rationale;
    }

    /**
     * Set fullname.
     *
     * @param string|null $fullname
     *
     * @return SpecificationMaterial
     */
    public function setFullname($fullname = null)
    {
        $this->fullname = $fullname;

        return $this;
    }

    /**
     * Get fullname.
     *
     * @return string|null
     */
    public function getFullname()
    {
        return $this->fullname;
    }

    /**
     * Set description.
     *
     * @param string|null $description
     *
     * @return SpecificationMaterial
     */
    public function setDescription($description = null)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description.
     *
     * @return string|null
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set type.
     *
     * @param string|null $type
     *
     * @return SpecificationMaterial
     */
    public function setType($type = null)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type.
     *
     * @return string|null
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set code.
     *
     * @param string|null $code
     *
     * @return SpecificationMaterial
     */
    public function setCode($code = null)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code.
     *
     * @return string|null
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set vendor.
     *
     * @param string|null $vendor
     *
     * @return SpecificationMaterial
     */
    public function setVendor($vendor = null)
    {
        $this->vendor = $vendor;

        return $this;
    }

    /**
     * Get vendor.
     *
     * @return string|null
     */
    public function getVendor()
    {
        return $this->vendor;
    }

    /**
     * Set quantity.
     *
     * @param float|null $quantity
     *
     * @return SpecificationMaterial
     */
    public function setQuantity($quantity = null)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * Get quantity.
     *
     * @return float|null
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Set massUnit.
     *
     * @param float|null $massUnit
     *
     * @return SpecificationMaterial
     */
    public function setMassUnit($massUnit = null)
    {
        $this->massUnit = $massUnit;

        return $this;
    }

    /**
     * Get massUnit.
     *
     * @return float|null
     */
    public function getMassUnit()
    {
        return $this->massUnit;
    }

    /**
     * Set isGroup.
     *
     * @param bool|null $isGroup
     *
     * @return SpecificationMaterial
     */
    public function setIsGroup($isGroup = null)
    {
        $this->isGroup = $isGroup;

        return $this;
    }

    /**
     * Get isGroup.
     *
     * @return bool|null
     */
    public function getIsGroup()
    {
        return $this->isGroup;
    }

    /**
     * Set createdAt.
     *
     * @param \DateTime $createdAt
     *
     * @return SpecificationMaterial
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
     * @return SpecificationMaterial
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
     * @return SpecificationMaterial
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
     * @return SpecificationMaterial
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
     * Set draft.
     *
     * @param bool|null $draft
     *
     * @return SpecificationMaterial
     */
    public function setDraft($draft = null)
    {
        $this->draft = $draft;

        return $this;
    }

    /**
     * Get draft.
     *
     * @return bool|null
     */
    public function getDraft()
    {
        return $this->draft;
    }

    /**
     * Set delete.
     *
     * @param bool|null $delete
     *
     * @return SpecificationMaterial
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
     * Set deletedAt.
     *
     * @param \DateTime|null $deletedAt
     *
     * @return SpecificationMaterial
     */
    public function setDeletedAt($deletedAt = null)
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }

    /**
     * Get deletedAt.
     *
     * @return \DateTime|null
     */
    public function getDeletedAt()
    {
        return $this->deletedAt;
    }

    /**
     * Set parent.
     *
     * @param \Domain\Entities\Business\Objects\SpecificationMaterial|null $parent
     *
     * @return SpecificationMaterial
     */
    public function setParent(\Domain\Entities\Business\Objects\SpecificationMaterial $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent.
     *
     * @return \Domain\Entities\Business\Objects\SpecificationMaterial|null
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Set unit.
     *
     * @param \Domain\Entities\Utils\Units|null $unit
     *
     * @return SpecificationMaterial
     */
    public function setUnit(\Domain\Entities\Utils\Units $unit = null)
    {
        $this->unit = $unit;

        return $this;
    }

    /**
     * Get unit.
     *
     * @return \Domain\Entities\Utils\Units|null
     */
    public function getUnit()
    {
        return $this->unit;
    }

    /**
     * Set material.
     *
     * @param \Domain\Entities\Business\Directory\Material|null $material
     *
     * @return SpecificationMaterial
     */
    public function setMaterial(\Domain\Entities\Business\Directory\Material $material = null)
    {
        $this->material = $material;

        return $this;
    }

    /**
     * Get material.
     *
     * @return \Domain\Entities\Business\Directory\Material|null
     */
    public function getMaterial()
    {
        return $this->material;
    }

    /**
     * Add specification.
     *
     * @param \Domain\Entities\Business\Objects\Specification $specification
     *
     * @return SpecificationMaterial
     */
    public function addSpecification(\Domain\Entities\Business\Objects\Specification $specification)
    {
        $this->specification[] = $specification;

        return $this;
    }

    /**
     * Remove specification.
     *
     * @param \Domain\Entities\Business\Objects\Specification $specification
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeSpecification(\Domain\Entities\Business\Objects\Specification $specification)
    {
        return $this->specification->removeElement($specification);
    }

    /**
     * Get specification.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSpecification()
    {
        return $this->specification;
    }
}
