<?php

namespace Domain\Entities\Business\Directory;

use Doctrine\ORM\Mapping as ORM;

/**
 * Material
 *
 * @ORM\Table(name="material", indexes={@ORM\Index(name="name_material_index", columns={"name"}), @ORM\Index(name="articul_material_index", columns={"articul"}), @ORM\Index(name="description_material_index", columns={"description"}), @ORM\Index(name="id_material_index", columns={"id"})})
 * @ORM\Entity
 */
class Material
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
     * @ORM\Column(name="name", type="string", nullable=false)
     */
    private $name;

    /**
     * @var string|null
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

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
     * @var string|null
     *
     * @ORM\Column(name="articul", type="string", nullable=true)
     */
    private $articul;

    /**
     * @var int|null
     *
     * @ORM\Column(name="sysid", type="integer", nullable=true)
     */
    private $sysid;

    /**
     * @var uuid|null
     *
     * @ORM\Column(name="autor_uuid", type="uuid", nullable=true)
     */
    private $autorId;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="isGroup", type="boolean", nullable=true)
     */
    private $isGroup = false;

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
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Domain\Entities\Business\Directory\Material", mappedBy="parent")
     * @ORM\OrderBy({
     *     "name"="ASC"
     * })
     */
    private $children;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Domain\Entities\Business\Objects\SpecificationMaterial", mappedBy="material")
     */
    private $specificationMaterial;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Domain\Entities\Business\Document\Requisition\Invoice\Material", mappedBy="directoryMaterial")
     */
    private $requisitionInvoiceMaterial;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Domain\Entities\Business\Master\RequisitionMaterials", mappedBy="directoryMaterial")
     */
    private $requisitionMasterMaterial;

    /**
     * @var \Domain\Entities\Business\Company\Company
     *
     * @ORM\ManyToOne(targetEntity="Domain\Entities\Business\Company\Company", inversedBy="material")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="company_id", referencedColumnName="id")
     * })
     */
    private $company;

    /**
     * @var \Domain\Entities\Business\Directory\Material
     *
     * @ORM\ManyToOne(targetEntity="Domain\Entities\Business\Directory\Material", inversedBy="children")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     * })
     */
    private $parent;

    /**
     * @var \Domain\Entities\Utils\Units
     *
     * @ORM\ManyToOne(targetEntity="Domain\Entities\Utils\Units", inversedBy="material")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="unit_id", referencedColumnName="id")
     * })
     */
    private $unit;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->children = new \Doctrine\Common\Collections\ArrayCollection();
        $this->specificationMaterial = new \Doctrine\Common\Collections\ArrayCollection();
        $this->requisitionInvoiceMaterial = new \Doctrine\Common\Collections\ArrayCollection();
        $this->requisitionMasterMaterial = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set name.
     *
     * @param string $name
     *
     * @return Material
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
     * Set description.
     *
     * @param string|null $description
     *
     * @return Material
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
     * Set code.
     *
     * @param string|null $code
     *
     * @return Material
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
     * @return Material
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
     * Set articul.
     *
     * @param string|null $articul
     *
     * @return Material
     */
    public function setArticul($articul = null)
    {
        $this->articul = $articul;

        return $this;
    }

    /**
     * Get articul.
     *
     * @return string|null
     */
    public function getArticul()
    {
        return $this->articul;
    }

    /**
     * Set sysid.
     *
     * @param int|null $sysid
     *
     * @return Material
     */
    public function setSysid($sysid = null)
    {
        $this->sysid = $sysid;

        return $this;
    }

    /**
     * Get sysid.
     *
     * @return int|null
     */
    public function getSysid()
    {
        return $this->sysid;
    }

    /**
     * Set autorId.
     *
     * @param uuid|null $autorId
     *
     * @return Material
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
     * Set isGroup.
     *
     * @param bool|null $isGroup
     *
     * @return Material
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
     * Set active.
     *
     * @param bool|null $active
     *
     * @return Material
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
     * @return Material
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
     * Set createdAt.
     *
     * @param \DateTime $createdAt
     *
     * @return Material
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
     * @return Material
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
     * @return Material
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
     * Add child.
     *
     * @param \Domain\Entities\Business\Directory\Material $child
     *
     * @return Material
     */
    public function addChild(\Domain\Entities\Business\Directory\Material $child)
    {
        $this->children[] = $child;

        return $this;
    }

    /**
     * Remove child.
     *
     * @param \Domain\Entities\Business\Directory\Material $child
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeChild(\Domain\Entities\Business\Directory\Material $child)
    {
        return $this->children->removeElement($child);
    }

    /**
     * Get children.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * Add specificationMaterial.
     *
     * @param \Domain\Entities\Business\Objects\SpecificationMaterial $specificationMaterial
     *
     * @return Material
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
     * Add requisitionInvoiceMaterial.
     *
     * @param \Domain\Entities\Business\Document\Requisition\Invoice\Material $requisitionInvoiceMaterial
     *
     * @return Material
     */
    public function addRequisitionInvoiceMaterial(\Domain\Entities\Business\Document\Requisition\Invoice\Material $requisitionInvoiceMaterial)
    {
        $this->requisitionInvoiceMaterial[] = $requisitionInvoiceMaterial;

        return $this;
    }

    /**
     * Remove requisitionInvoiceMaterial.
     *
     * @param \Domain\Entities\Business\Document\Requisition\Invoice\Material $requisitionInvoiceMaterial
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeRequisitionInvoiceMaterial(\Domain\Entities\Business\Document\Requisition\Invoice\Material $requisitionInvoiceMaterial)
    {
        return $this->requisitionInvoiceMaterial->removeElement($requisitionInvoiceMaterial);
    }

    /**
     * Get requisitionInvoiceMaterial.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRequisitionInvoiceMaterial()
    {
        return $this->requisitionInvoiceMaterial;
    }

    /**
     * Add requisitionMasterMaterial.
     *
     * @param \Domain\Entities\Business\Master\RequisitionMaterials $requisitionMasterMaterial
     *
     * @return Material
     */
    public function addRequisitionMasterMaterial(\Domain\Entities\Business\Master\RequisitionMaterials $requisitionMasterMaterial)
    {
        $this->requisitionMasterMaterial[] = $requisitionMasterMaterial;

        return $this;
    }

    /**
     * Remove requisitionMasterMaterial.
     *
     * @param \Domain\Entities\Business\Master\RequisitionMaterials $requisitionMasterMaterial
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeRequisitionMasterMaterial(\Domain\Entities\Business\Master\RequisitionMaterials $requisitionMasterMaterial)
    {
        return $this->requisitionMasterMaterial->removeElement($requisitionMasterMaterial);
    }

    /**
     * Get requisitionMasterMaterial.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRequisitionMasterMaterial()
    {
        return $this->requisitionMasterMaterial;
    }

    /**
     * Set company.
     *
     * @param \Domain\Entities\Business\Company\Company|null $company
     *
     * @return Material
     */
    public function setCompany(\Domain\Entities\Business\Company\Company $company = null)
    {
        $this->company = $company;

        return $this;
    }

    /**
     * Get company.
     *
     * @return \Domain\Entities\Business\Company\Company|null
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * Set parent.
     *
     * @param \Domain\Entities\Business\Directory\Material|null $parent
     *
     * @return Material
     */
    public function setParent(\Domain\Entities\Business\Directory\Material $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent.
     *
     * @return \Domain\Entities\Business\Directory\Material|null
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
     * @return Material
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
