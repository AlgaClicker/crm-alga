<?php

namespace Domain\Entities\Business\Document\Requisition;

use Doctrine\ORM\Mapping as ORM;

/**
 * Invoice
 *
 * @ORM\Table(name="requisition_invoice")
 * @ORM\Entity
 */
class Invoice
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
     * @ORM\Column(name="number", type="string", nullable=false)
     */
    private $number;

    /**
     * @var int
     *
     * @ORM\Column(name="index", type="integer", nullable=false)
     */
    private $index;

    /**
     * @var string|null
     *
     * @ORM\Column(name="status", type="string", nullable=true)
     */
    private $status;

    /**
     * @var string|null
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="active", type="boolean", nullable=true)
     */
    private $active;

    /**
     * @var float
     *
     * @ORM\Column(name="amount", type="float", nullable=false)
     */
    private $amount;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="delivery_at", type="datetime", nullable=false)
     */
    private $deliveryAt;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=true)
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
     * @var bool|null
     *
     * @ORM\Column(name="fixed", type="boolean", nullable=true)
     */
    private $fixed = false;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Domain\Entities\Business\Document\Requisition\Invoice\Material", mappedBy="requisitionInvoice")
     */
    private $materials;

    /**
     * @var \Domain\Entities\Business\Company\Company
     *
     * @ORM\ManyToOne(targetEntity="Domain\Entities\Business\Company\Company")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="company", referencedColumnName="id")
     * })
     */
    private $company;

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
     * @var \Domain\Entities\Business\Master\Requisition
     *
     * @ORM\ManyToOne(targetEntity="Domain\Entities\Business\Master\Requisition", inversedBy="invoices")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="requisition_id", referencedColumnName="id")
     * })
     */
    private $requisition;

    /**
     * @var \Domain\Entities\Business\Directory\Partner
     *
     * @ORM\ManyToOne(targetEntity="Domain\Entities\Business\Directory\Partner", inversedBy="requisitionInvoice")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="partner_id", referencedColumnName="id")
     * })
     */
    private $partner;

    /**
     * @var \Domain\Entities\Business\Objects\Specification
     *
     * @ORM\ManyToOne(targetEntity="Domain\Entities\Business\Objects\Specification", inversedBy="requisitionInvoice", cascade={"persist","merge"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="specification_id", referencedColumnName="id")
     * })
     */
    private $specification;

    /**
     * @var \Domain\Entities\Business\Document\Contracts
     *
     * @ORM\ManyToOne(targetEntity="Domain\Entities\Business\Document\Contracts", inversedBy="invoices")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="contract_id", referencedColumnName="id")
     * })
     */
    private $contract;

    /**
     * @var \Domain\Entities\Business\Company\Stock
     *
     * @ORM\ManyToOne(targetEntity="Domain\Entities\Business\Company\Stock", inversedBy="requisitionInvoice")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="stock_id", referencedColumnName="id")
     * })
     */
    private $stock;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Domain\Entities\Services\Files", mappedBy="requisitionInvoice")
     */
    private $files = array();

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->materials = new \Doctrine\Common\Collections\ArrayCollection();
        $this->files = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set number.
     *
     * @param string $number
     *
     * @return Invoice
     */
    public function setNumber($number)
    {
        $this->number = $number;

        return $this;
    }

    /**
     * Get number.
     *
     * @return string
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * Set index.
     *
     * @param int $index
     *
     * @return Invoice
     */
    public function setIndex($index)
    {
        $this->index = $index;

        return $this;
    }

    /**
     * Get index.
     *
     * @return int
     */
    public function getIndex()
    {
        return $this->index;
    }

    /**
     * Set status.
     *
     * @param string|null $status
     *
     * @return Invoice
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
     * Set description.
     *
     * @param string|null $description
     *
     * @return Invoice
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
     * Set active.
     *
     * @param bool|null $active
     *
     * @return Invoice
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
     * Set amount.
     *
     * @param float $amount
     *
     * @return Invoice
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get amount.
     *
     * @return float
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Set deliveryAt.
     *
     * @param \DateTime $deliveryAt
     *
     * @return Invoice
     */
    public function setDeliveryAt($deliveryAt)
    {
        $this->deliveryAt = $deliveryAt;

        return $this;
    }

    /**
     * Get deliveryAt.
     *
     * @return \DateTime
     */
    public function getDeliveryAt()
    {
        return $this->deliveryAt;
    }

    /**
     * Set createdAt.
     *
     * @param \DateTime|null $createdAt
     *
     * @return Invoice
     */
    public function setCreatedAt($createdAt = null)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt.
     *
     * @return \DateTime|null
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
     * @return Invoice
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
     * @return Invoice
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
     * @return Invoice
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
     * Set fixed.
     *
     * @param bool|null $fixed
     *
     * @return Invoice
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
     * Add material.
     *
     * @param \Domain\Entities\Business\Document\Requisition\Invoice\Material $material
     *
     * @return Invoice
     */
    public function addMaterial(\Domain\Entities\Business\Document\Requisition\Invoice\Material $material)
    {
        $this->materials[] = $material;

        return $this;
    }

    /**
     * Remove material.
     *
     * @param \Domain\Entities\Business\Document\Requisition\Invoice\Material $material
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeMaterial(\Domain\Entities\Business\Document\Requisition\Invoice\Material $material)
    {
        return $this->materials->removeElement($material);
    }

    /**
     * Get materials.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMaterials()
    {
        return $this->materials;
    }

    /**
     * Set company.
     *
     * @param \Domain\Entities\Business\Company\Company|null $company
     *
     * @return Invoice
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
     * Set autor.
     *
     * @param \Domain\Entities\Subscriber\Account|null $autor
     *
     * @return Invoice
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
     * Set requisition.
     *
     * @param \Domain\Entities\Business\Master\Requisition|null $requisition
     *
     * @return Invoice
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
     * Set partner.
     *
     * @param \Domain\Entities\Business\Directory\Partner|null $partner
     *
     * @return Invoice
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
     * Set specification.
     *
     * @param \Domain\Entities\Business\Objects\Specification|null $specification
     *
     * @return Invoice
     */
    public function setSpecification(\Domain\Entities\Business\Objects\Specification $specification = null)
    {
        $this->specification = $specification;

        return $this;
    }

    /**
     * Get specification.
     *
     * @return \Domain\Entities\Business\Objects\Specification|null
     */
    public function getSpecification()
    {
        return $this->specification;
    }

    /**
     * Set contract.
     *
     * @param \Domain\Entities\Business\Document\Contracts|null $contract
     *
     * @return Invoice
     */
    public function setContract(\Domain\Entities\Business\Document\Contracts $contract = null)
    {
        $this->contract = $contract;

        return $this;
    }

    /**
     * Get contract.
     *
     * @return \Domain\Entities\Business\Document\Contracts|null
     */
    public function getContract()
    {
        return $this->contract;
    }

    /**
     * Set stock.
     *
     * @param \Domain\Entities\Business\Company\Stock|null $stock
     *
     * @return Invoice
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
     * Add file.
     *
     * @param \Domain\Entities\Services\Files $file
     *
     * @return Invoice
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
}
