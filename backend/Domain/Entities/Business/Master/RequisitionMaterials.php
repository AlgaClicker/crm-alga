<?php

namespace Domain\Entities\Business\Master;

use Doctrine\ORM\Mapping as ORM;

/**
 * RequisitionMaterials
 *
 * @ORM\Table(name="master_requisition_materials", indexes={@ORM\Index(name="id_material_requisitions_index", columns={"id"})})
 * @ORM\Entity
 */
class RequisitionMaterials
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
     * @ORM\Column(name="name", type="string", nullable=true)
     */
    private $name;

    /**
     * @var float
     *
     * @ORM\Column(name="quantity", type="float", nullable=false)
     */
    private $quantity;

    /**
     * @var string|null
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var string|null
     *
     * @ORM\Column(name="status", type="string", nullable=true)
     */
    private $status;

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
     * @var \DateTime|null
     *
     * @ORM\Column(name="deleted_at", type="datetime", nullable=true)
     */
    private $deletedAt;

    /**
     * @var \Domain\Entities\Business\Master\Requisition
     *
     * @ORM\ManyToOne(targetEntity="Domain\Entities\Business\Master\Requisition", inversedBy="materials")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="requisition_id", referencedColumnName="id")
     * })
     */
    private $requisition;

    /**
     * @var \Domain\Entities\Business\Directory\Material
     *
     * @ORM\ManyToOne(targetEntity="Domain\Entities\Business\Directory\Material", inversedBy="requisitionMasterMaterial")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="directory_material_id", referencedColumnName="id")
     * })
     */
    private $directoryMaterial;

    /**
     * @var \Domain\Entities\Business\Objects\SpecificationMaterial
     *
     * @ORM\ManyToOne(targetEntity="Domain\Entities\Business\Objects\SpecificationMaterial")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="specification_material", referencedColumnName="id")
     * })
     */
    private $specificationMaterial;

    /**
     * @var \Domain\Entities\Subscriber\Account
     *
     * @ORM\ManyToOne(targetEntity="Domain\Entities\Subscriber\Account")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="autor", referencedColumnName="id")
     * })
     */
    private $autor;

    /**
     * @var \Domain\Entities\Utils\Units
     *
     * @ORM\ManyToOne(targetEntity="Domain\Entities\Utils\Units")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="unit", referencedColumnName="id")
     * })
     */
    private $unit;


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
     * @return RequisitionMaterials
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
     * Set name.
     *
     * @param string|null $name
     *
     * @return RequisitionMaterials
     */
    public function setName($name = null)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name.
     *
     * @return string|null
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set quantity.
     *
     * @param float $quantity
     *
     * @return RequisitionMaterials
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * Get quantity.
     *
     * @return float
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Set description.
     *
     * @param string|null $description
     *
     * @return RequisitionMaterials
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
     * Set status.
     *
     * @param string|null $status
     *
     * @return RequisitionMaterials
     */
    public function setStatus($status = null)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status.
     *
     * @return string|null
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set createdAt.
     *
     * @param \DateTime $createdAt
     *
     * @return RequisitionMaterials
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
     * @return RequisitionMaterials
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
     * Set deletedAt.
     *
     * @param \DateTime|null $deletedAt
     *
     * @return RequisitionMaterials
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
     * Set requisition.
     *
     * @param \Domain\Entities\Business\Master\Requisition|null $requisition
     *
     * @return RequisitionMaterials
     */
    public function setRequisition(\Domain\Entities\Business\Master\Requisition $requisition = null)
    {
        $this->requisition = $requisition;

        return $this;
    }

    /**
     * Get requisition.
     *
     * @return \Domain\Entities\Business\Master\Requisition|null
     */
    public function getRequisition()
    {
        return $this->requisition;
    }

    /**
     * Set directoryMaterial.
     *
     * @param \Domain\Entities\Business\Directory\Material|null $directoryMaterial
     *
     * @return RequisitionMaterials
     */
    public function setDirectoryMaterial(\Domain\Entities\Business\Directory\Material $directoryMaterial = null)
    {
        $this->directoryMaterial = $directoryMaterial;

        return $this;
    }

    /**
     * Get directoryMaterial.
     *
     * @return \Domain\Entities\Business\Directory\Material|null
     */
    public function getDirectoryMaterial()
    {
        return $this->directoryMaterial;
    }

    /**
     * Set specificationMaterial.
     *
     * @param \Domain\Entities\Business\Objects\SpecificationMaterial|null $specificationMaterial
     *
     * @return RequisitionMaterials
     */
    public function setSpecificationMaterial(\Domain\Entities\Business\Objects\SpecificationMaterial $specificationMaterial = null)
    {
        $this->specificationMaterial = $specificationMaterial;

        return $this;
    }

    /**
     * Get specificationMaterial.
     *
     * @return \Domain\Entities\Business\Objects\SpecificationMaterial|null
     */
    public function getSpecificationMaterial()
    {
        return $this->specificationMaterial;
    }

    /**
     * Set autor.
     *
     * @param \Domain\Entities\Subscriber\Account|null $autor
     *
     * @return RequisitionMaterials
     */
    public function setAutor(\Domain\Entities\Subscriber\Account $autor = null)
    {
        $this->autor = $autor;

        return $this;
    }

    /**
     * Get autor.
     *
     * @return \Domain\Entities\Subscriber\Account|null
     */
    public function getAutor()
    {
        return $this->autor;
    }

    /**
     * Set unit.
     *
     * @param \Domain\Entities\Utils\Units|null $unit
     *
     * @return RequisitionMaterials
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
}
