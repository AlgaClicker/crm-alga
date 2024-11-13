<?php

namespace Domain\Entities\Business\Objects;

use Doctrine\ORM\Mapping as ORM;

/**
 * Specification
 *
 * @ORM\Table(name="specification")
 * @ORM\Entity
 */
class Specification
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
     * @ORM\Column(name="LocNum", type="string", nullable=true)
     */
    private $locnum;

    /**
     * @var string|null
     *
     * @ORM\Column(name="number", type="string", nullable=true)
     */
    private $number;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Constr", type="string", nullable=true)
     */
    private $constr;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string")
     */
    private $description;

    /**
     * @var string|null
     *
     * @ORM\Column(name="type", type="string", nullable=true)
     */
    private $type;

    /**
     * @var int|null
     *
     * @ORM\Column(name="idx", type="integer", nullable=true)
     */
    private $idx;

    /**
     * @var uuid
     *
     * @ORM\Column(name="autor_uuid", type="uuid", nullable=false)
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
     * @ORM\Column(name="draft", type="boolean", nullable=true, options={"default"="1"})
     */
    private $draft = true;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="fixed", type="boolean", nullable=true)
     */
    private $fixed = false;

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
     * @var \DateTime|null
     *
     * @ORM\Column(name="archive_at", type="datetime", nullable=true)
     */
    private $archiveAt;

    /**
     * @var \Domain\Entities\Business\Company\Stock
     *
     * @ORM\OneToOne(targetEntity="Domain\Entities\Business\Company\Stock")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="stock_id", referencedColumnName="id", unique=true)
     * })
     */
    private $stock;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Domain\Entities\Business\Objects\Specification", mappedBy="parent", cascade={"persist","merge"})
     * @ORM\OrderBy({
     *     "idx"="ASC"
     * })
     */
    private $children;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Domain\Entities\Business\Objects\SpecificationTypeWorks", mappedBy="specification")
     */
    private $specificationTypeworks;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Domain\Entities\Business\Objects\SpecificationResources", mappedBy="specification")
     */
    private $specificationResources;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Domain\Entities\Business\Master\Requisition", mappedBy="specification")
     */
    private $requisitions;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Domain\Entities\Business\Document\Requisition\Invoice", mappedBy="specification")
     */
    private $requisitionInvoice;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Domain\Entities\Business\Document\BrigadeSpecification", mappedBy="specification")
     */
    private $brigadeSpecification;

    /**
     * @var \Domain\Entities\Business\Objects\Objects
     *
     * @ORM\ManyToOne(targetEntity="Domain\Entities\Business\Objects\Objects", inversedBy="specification")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="object_id", referencedColumnName="id")
     * })
     */
    private $object;

    /**
     * @var \Domain\Entities\Business\Objects\Specification
     *
     * @ORM\ManyToOne(targetEntity="Domain\Entities\Business\Objects\Specification", inversedBy="children", cascade={"persist","merge"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     * })
     */
    private $parent;

    /**
     * @var \Domain\Entities\Subscriber\Account
     *
     * @ORM\ManyToOne(targetEntity="Domain\Entities\Subscriber\Account")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="autor_id", referencedColumnName="id")
     * })
     */
    private $autor;

    /**
     * @var \Domain\Entities\Subscriber\Account
     *
     * @ORM\ManyToOne(targetEntity="Domain\Entities\Subscriber\Account")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="manager_id", referencedColumnName="id")
     * })
     */
    private $manager;

    /**
     * @var \Domain\Entities\Business\Directory\Partner
     *
     * @ORM\ManyToOne(targetEntity="Domain\Entities\Business\Directory\Partner")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="partner_id", referencedColumnName="id")
     * })
     */
    private $partner;

    /**
     * @var \Domain\Entities\Business\Company\Company
     *
     * @ORM\ManyToOne(targetEntity="Domain\Entities\Business\Company\Company")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="company_id", referencedColumnName="id")
     * })
     */
    private $company;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Domain\Entities\Business\Objects\SpecificationMaterial", mappedBy="specification")
     * @ORM\OrderBy({
     *     "index"="ASC",
     *     "position"="ASC"
     * })
     */
    private $specificationMaterials = array();

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Domain\Entities\Services\Files", inversedBy="specification")
     * @ORM\JoinTable(name="files_specification",
     *   joinColumns={
     *     @ORM\JoinColumn(name="specification_id", referencedColumnName="id", onDelete="CASCADE")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="files_id", referencedColumnName="id", onDelete="CASCADE")
     *   }
     * )
     */
    private $files = array();

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Domain\Entities\Subscriber\Account")
     * @ORM\JoinTable(name="specification_responsible",
     *   joinColumns={
     *     @ORM\JoinColumn(name="specification_id", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="account_id", referencedColumnName="id")
     *   }
     * )
     */
    private $responsibles = array();

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Domain\Entities\Business\Document\Contracts", mappedBy="specifications")
     */
    private $contracts = array();

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->children = new \Doctrine\Common\Collections\ArrayCollection();
        $this->specificationTypeworks = new \Doctrine\Common\Collections\ArrayCollection();
        $this->specificationResources = new \Doctrine\Common\Collections\ArrayCollection();
        $this->requisitions = new \Doctrine\Common\Collections\ArrayCollection();
        $this->requisitionInvoice = new \Doctrine\Common\Collections\ArrayCollection();
        $this->brigadeSpecification = new \Doctrine\Common\Collections\ArrayCollection();
        $this->specificationMaterials = new \Doctrine\Common\Collections\ArrayCollection();
        $this->files = new \Doctrine\Common\Collections\ArrayCollection();
        $this->responsibles = new \Doctrine\Common\Collections\ArrayCollection();
        $this->contracts = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Specification
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
     * Set locnum.
     *
     * @param string|null $locnum
     *
     * @return Specification
     */
    public function setLocnum($locnum = null)
    {
        $this->locnum = $locnum;

        return $this;
    }

    /**
     * Get locnum.
     *
     * @return string|null
     */
    public function getLocnum()
    {
        return $this->locnum;
    }

    /**
     * Set number.
     *
     * @param string|null $number
     *
     * @return Specification
     */
    public function setNumber($number = null)
    {
        $this->number = $number;

        return $this;
    }

    /**
     * Get number.
     *
     * @return string|null
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * Set constr.
     *
     * @param string|null $constr
     *
     * @return Specification
     */
    public function setConstr($constr = null)
    {
        $this->constr = $constr;

        return $this;
    }

    /**
     * Get constr.
     *
     * @return string|null
     */
    public function getConstr()
    {
        return $this->constr;
    }

    /**
     * Set description.
     *
     * @param string $description
     *
     * @return Specification
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description.
     *
     * @return string
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
     * @return Specification
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
     * Set idx.
     *
     * @param int|null $idx
     *
     * @return Specification
     */
    public function setIdx($idx = null)
    {
        $this->idx = $idx;

        return $this;
    }

    /**
     * Get idx.
     *
     * @return int|null
     */
    public function getIdx()
    {
        return $this->idx;
    }

    /**
     * Set autorId.
     *
     * @param uuid $autorId
     *
     * @return Specification
     */
    public function setAutorId($autorId)
    {
        $this->autorId = $autorId;

        return $this;
    }

    /**
     * Get autorId.
     *
     * @return uuid
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
     * @return Specification
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
     * @return Specification
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
     * Set fixed.
     *
     * @param bool|null $fixed
     *
     * @return Specification
     */
    public function setFixed($fixed = null)
    {
        $this->fixed = $fixed;

        return $this;
    }

    /**
     * Get fixed.
     *
     * @return bool|null
     */
    public function getFixed()
    {
        return $this->fixed;
    }

    /**
     * Set delete.
     *
     * @param bool|null $delete
     *
     * @return Specification
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
     * @return Specification
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
     * @return Specification
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
     * @return Specification
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
     * Set archiveAt.
     *
     * @param \DateTime|null $archiveAt
     *
     * @return Specification
     */
    public function setArchiveAt($archiveAt = null)
    {
        $this->archiveAt = $archiveAt;

        return $this;
    }

    /**
     * Get archiveAt.
     *
     * @return \DateTime|null
     */
    public function getArchiveAt()
    {
        return $this->archiveAt;
    }

    /**
     * Set stock.
     *
     * @param \Domain\Entities\Business\Company\Stock|null $stock
     *
     * @return Specification
     */
    public function setStock(\Domain\Entities\Business\Company\Stock $stock = null)
    {
        $this->stock = $stock;

        return $this;
    }

    /**
     * Get stock.
     *
     * @return \Domain\Entities\Business\Company\Stock|null
     */
    public function getStock()
    {
        return $this->stock;
    }

    /**
     * Add child.
     *
     * @param \Domain\Entities\Business\Objects\Specification $child
     *
     * @return Specification
     */
    public function addChild(\Domain\Entities\Business\Objects\Specification $child)
    {
        $this->children[] = $child;

        return $this;
    }

    /**
     * Remove child.
     *
     * @param \Domain\Entities\Business\Objects\Specification $child
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeChild(\Domain\Entities\Business\Objects\Specification $child)
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
     * Add specificationTypework.
     *
     * @param \Domain\Entities\Business\Objects\SpecificationTypeWorks $specificationTypework
     *
     * @return Specification
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

    /**
     * Add specificationResource.
     *
     * @param \Domain\Entities\Business\Objects\SpecificationResources $specificationResource
     *
     * @return Specification
     */
    public function addSpecificationResource(\Domain\Entities\Business\Objects\SpecificationResources $specificationResource)
    {
        $this->specificationResources[] = $specificationResource;

        return $this;
    }

    /**
     * Remove specificationResource.
     *
     * @param \Domain\Entities\Business\Objects\SpecificationResources $specificationResource
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeSpecificationResource(\Domain\Entities\Business\Objects\SpecificationResources $specificationResource)
    {
        return $this->specificationResources->removeElement($specificationResource);
    }

    /**
     * Get specificationResources.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSpecificationResources()
    {
        return $this->specificationResources;
    }

    /**
     * Add requisition.
     *
     * @param \Domain\Entities\Business\Master\Requisition $requisition
     *
     * @return Specification
     */
    public function addRequisition(\Domain\Entities\Business\Master\Requisition $requisition)
    {
        $this->requisitions[] = $requisition;

        return $this;
    }

    /**
     * Remove requisition.
     *
     * @param \Domain\Entities\Business\Master\Requisition $requisition
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeRequisition(\Domain\Entities\Business\Master\Requisition $requisition)
    {
        return $this->requisitions->removeElement($requisition);
    }

    /**
     * Get requisitions.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRequisitions()
    {
        return $this->requisitions;
    }

    /**
     * Add requisitionInvoice.
     *
     * @param \Domain\Entities\Business\Document\Requisition\Invoice $requisitionInvoice
     *
     * @return Specification
     */
    public function addRequisitionInvoice(\Domain\Entities\Business\Document\Requisition\Invoice $requisitionInvoice)
    {
        $this->requisitionInvoice[] = $requisitionInvoice;

        return $this;
    }

    /**
     * Remove requisitionInvoice.
     *
     * @param \Domain\Entities\Business\Document\Requisition\Invoice $requisitionInvoice
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeRequisitionInvoice(\Domain\Entities\Business\Document\Requisition\Invoice $requisitionInvoice)
    {
        return $this->requisitionInvoice->removeElement($requisitionInvoice);
    }

    /**
     * Get requisitionInvoice.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRequisitionInvoice()
    {
        return $this->requisitionInvoice;
    }

    /**
     * Add brigadeSpecification.
     *
     * @param \Domain\Entities\Business\Document\BrigadeSpecification $brigadeSpecification
     *
     * @return Specification
     */
    public function addBrigadeSpecification(\Domain\Entities\Business\Document\BrigadeSpecification $brigadeSpecification)
    {
        $this->brigadeSpecification[] = $brigadeSpecification;

        return $this;
    }

    /**
     * Remove brigadeSpecification.
     *
     * @param \Domain\Entities\Business\Document\BrigadeSpecification $brigadeSpecification
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeBrigadeSpecification(\Domain\Entities\Business\Document\BrigadeSpecification $brigadeSpecification)
    {
        return $this->brigadeSpecification->removeElement($brigadeSpecification);
    }

    /**
     * Get brigadeSpecification.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getBrigadeSpecification()
    {
        return $this->brigadeSpecification;
    }

    /**
     * Set object.
     *
     * @param \Domain\Entities\Business\Objects\Objects|null $object
     *
     * @return Specification
     */
    public function setObject(\Domain\Entities\Business\Objects\Objects $object = null)
    {
        $this->object = $object;

        return $this;
    }

    /**
     * Get object.
     *
     * @return \Domain\Entities\Business\Objects\Objects|null
     */
    public function getObject()
    {
        return $this->object;
    }

    /**
     * Set parent.
     *
     * @param \Domain\Entities\Business\Objects\Specification|null $parent
     *
     * @return Specification
     */
    public function setParent(\Domain\Entities\Business\Objects\Specification $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent.
     *
     * @return \Domain\Entities\Business\Objects\Specification|null
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Set autor.
     *
     * @param \Domain\Entities\Subscriber\Account|null $autor
     *
     * @return Specification
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
     * Set manager.
     *
     * @param \Domain\Entities\Subscriber\Account|null $manager
     *
     * @return Specification
     */
    public function setManager(\Domain\Entities\Subscriber\Account $manager = null)
    {
        $this->manager = $manager;

        return $this;
    }

    /**
     * Get manager.
     *
     * @return \Domain\Entities\Subscriber\Account|null
     */
    public function getManager()
    {
        return $this->manager;
    }

    /**
     * Set partner.
     *
     * @param \Domain\Entities\Business\Directory\Partner|null $partner
     *
     * @return Specification
     */
    public function setPartner(\Domain\Entities\Business\Directory\Partner $partner = null)
    {
        $this->partner = $partner;

        return $this;
    }

    /**
     * Get partner.
     *
     * @return \Domain\Entities\Business\Directory\Partner|null
     */
    public function getPartner()
    {
        return $this->partner;
    }

    /**
     * Set company.
     *
     * @param \Domain\Entities\Business\Company\Company|null $company
     *
     * @return Specification
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
     * Add specificationMaterial.
     *
     * @param \Domain\Entities\Business\Objects\SpecificationMaterial $specificationMaterial
     *
     * @return Specification
     */
    public function addSpecificationMaterial(\Domain\Entities\Business\Objects\SpecificationMaterial $specificationMaterial)
    {
        $this->specificationMaterials[] = $specificationMaterial;

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
        return $this->specificationMaterials->removeElement($specificationMaterial);
    }

    /**
     * Get specificationMaterials.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSpecificationMaterials()
    {
        return $this->specificationMaterials;
    }

    /**
     * Add file.
     *
     * @param \Domain\Entities\Services\Files $file
     *
     * @return Specification
     */
    public function addFile(\Domain\Entities\Services\Files $file)
    {
        $this->files[] = $file;

        return $this;
    }

    /**
     * Remove file.
     *
     * @param \Domain\Entities\Services\Files $file
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeFile(\Domain\Entities\Services\Files $file)
    {
        return $this->files->removeElement($file);
    }

    /**
     * Get files.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFiles()
    {
        return $this->files;
    }

    /**
     * Add responsible.
     *
     * @param \Domain\Entities\Subscriber\Account $responsible
     *
     * @return Specification
     */
    public function addResponsible(\Domain\Entities\Subscriber\Account $responsible)
    {
        $this->responsibles[] = $responsible;

        return $this;
    }

    /**
     * Remove responsible.
     *
     * @param \Domain\Entities\Subscriber\Account $responsible
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeResponsible(\Domain\Entities\Subscriber\Account $responsible)
    {
        return $this->responsibles->removeElement($responsible);
    }

    /**
     * Get responsibles.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getResponsibles()
    {
        return $this->responsibles;
    }

    /**
     * Add contract.
     *
     * @param \Domain\Entities\Business\Document\Contracts $contract
     *
     * @return Specification
     */
    public function addContract(\Domain\Entities\Business\Document\Contracts $contract)
    {
        $this->contracts[] = $contract;

        return $this;
    }

    /**
     * Remove contract.
     *
     * @param \Domain\Entities\Business\Document\Contracts $contract
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeContract(\Domain\Entities\Business\Document\Contracts $contract)
    {
        return $this->contracts->removeElement($contract);
    }

    /**
     * Get contracts.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getContracts()
    {
        return $this->contracts;
    }
}
