<?php

namespace Domain\Entities\Business\Document\Requisition\Invoice;

use Doctrine\ORM\Mapping as ORM;

/**
 * Material
 *
 * @ORM\Table(name="requisition_invoice_material")
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
     * @var int|null
     *
     * @ORM\Column(name="index", type="integer", nullable=true)
     */
    private $index;

    /**
     * @var float
     *
     * @ORM\Column(name="quantity", type="float", nullable=false)
     */
    private $quantity;

    /**
     * @var float
     *
     * @ORM\Column(name="price", type="float", nullable=false)
     */
    private $price;

    /**
     * @var string|null
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * @var float
     *
     * @ORM\Column(name="amount", type="float", nullable=false)
     */
    private $amount;

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
     * @var string|null
     *
     * @ORM\Column(name="status", type="string", nullable=true)
     */
    private $status;

    /**
     * @var float|null
     *
     * @ORM\Column(name="received", type="float", nullable=true)
     */
    private $received;

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
     * @var \Domain\Entities\Business\Document\Requisition\Invoice
     *
     * @ORM\ManyToOne(targetEntity="Domain\Entities\Business\Document\Requisition\Invoice", inversedBy="materials")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="requisition_invoice_id", referencedColumnName="id")
     * })
     */
    private $requisitionInvoice;

    /**
     * @var \Domain\Entities\Business\Directory\Material
     *
     * @ORM\ManyToOne(targetEntity="Domain\Entities\Business\Directory\Material", inversedBy="requisitionInvoiceMaterial")
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
     * @var \Domain\Entities\Business\Master\RequisitionMaterials
     *
     * @ORM\ManyToOne(targetEntity="Domain\Entities\Business\Master\RequisitionMaterials")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="requisition_material", referencedColumnName="id")
     * })
     */
    private $requisitionMaterial;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Domain\Entities\Services\Files", mappedBy="requisitionInvoiceMaterial")
     */
    private $files = array();

    /**
     * Constructor
     */
    public function __construct()
    {
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
     * Set index.
     *
     * @param int|null $index
     *
     * @return Material
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
     * Set quantity.
     *
     * @param float $quantity
     *
     * @return Material
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
     * Set price.
     *
     * @param float $price
     *
     * @return Material
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price.
     *
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
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
     * Set createdAt.
     *
     * @param \DateTime|null $createdAt
     *
     * @return Material
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
     * Set amount.
     *
     * @param float $amount
     *
     * @return Material
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
     * Set archiveAt.
     *
     * @param \DateTime|null $archiveAt
     *
     * @return Material
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
     * Set status.
     *
     * @param string|null $status
     *
     * @return Material
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
     * Set received.
     *
     * @param float|null $received
     *
     * @return Material
     */
    public function setReceived($received = null)
    {
        $this->received = $received;

        return $this;
    }

    /**
     * Get received.
     *
     * @return float|null
     */
    public function getReceived()
    {
        return $this->received;
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
     * Set autor.
     *
     * @param \Domain\Entities\Subscriber\Account|null $autor
     *
     * @return Material
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
     * Set requisitionInvoice.
     *
     * @param \Domain\Entities\Business\Document\Requisition\Invoice|null $requisitionInvoice
     *
     * @return Material
     */
    public function setRequisitionInvoice(\Domain\Entities\Business\Document\Requisition\Invoice $requisitionInvoice = null)
    {
        $this->requisitionInvoice = $requisitionInvoice;

        return $this;
    }

    /**
     * Get requisitionInvoice.
     *
     * @return \Domain\Entities\Business\Document\Requisition\Invoice|null
     */
    public function getRequisitionInvoice()
    {
        return $this->requisitionInvoice;
    }

    /**
     * Set directoryMaterial.
     *
     * @param \Domain\Entities\Business\Directory\Material|null $directoryMaterial
     *
     * @return Material
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
     * @return Material
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
     * Set requisitionMaterial.
     *
     * @param \Domain\Entities\Business\Master\RequisitionMaterials|null $requisitionMaterial
     *
     * @return Material
     */
    public function setRequisitionMaterial(\Domain\Entities\Business\Master\RequisitionMaterials $requisitionMaterial = null)
    {
        $this->requisitionMaterial = $requisitionMaterial;

        return $this;
    }

    /**
     * Get requisitionMaterial.
     *
     * @return \Domain\Entities\Business\Master\RequisitionMaterials|null
     */
    public function getRequisitionMaterial()
    {
        return $this->requisitionMaterial;
    }

    /**
     * Add file.
     *
     * @param \Domain\Entities\Services\Files $file
     *
     * @return Material
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
