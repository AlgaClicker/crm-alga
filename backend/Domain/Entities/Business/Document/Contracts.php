<?php

namespace Domain\Entities\Business\Document;

use Doctrine\ORM\Mapping as ORM;

/**
 * Contracts
 *
 * @ORM\Table(name="contracts", indexes={@ORM\Index(name="number_contracts_index", columns={"number"}), @ORM\Index(name="description_contracts_index", columns={"description"}), @ORM\Index(name="id_contracts_index", columns={"id"})})
 * @ORM\Entity
 */
class Contracts
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
     * @var string
     *
     * @ORM\Column(name="number", type="string", nullable=false)
     */
    private $number;

    /**
     * @var string|null
     *
     * @ORM\Column(name="number_sys", type="string", nullable=true)
     */
    private $numberSys;

    /**
     * @var string|null
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="start_at", type="datetime", nullable=false)
     */
    private $startAt;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="end_at", type="datetime", nullable=true)
     */
    private $endAt;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", nullable=false)
     */
    private $type;

    /**
     * @var int|null
     *
     * @ORM\Column(name="tax", type="integer", nullable=true)
     */
    private $tax;

    /**
     * @var float|null
     *
     * @ORM\Column(name="tax_amount", type="float", nullable=true)
     */
    private $taxAmount;

    /**
     * @var float|null
     *
     * @ORM\Column(name="amount", type="float", nullable=true)
     */
    private $amount;

    /**
     * @var float|null
     *
     * @ORM\Column(name="credit", type="float", nullable=true)
     */
    private $credit;

    /**
     * @var int|null
     *
     * @ORM\Column(name="credit_days", type="integer", nullable=true)
     */
    private $creditDays;

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
     * @var bool|null
     *
     * @ORM\Column(name="fixed", type="boolean", nullable=true)
     */
    private $fixed = false;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="active", type="boolean", nullable=true, options={"default"="1"})
     */
    private $active = true;

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
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Domain\Entities\Business\Document\Requisition\Invoice", mappedBy="contract")
     */
    private $invoices;

    /**
     * @var \Domain\Entities\Business\Company\Company
     *
     * @ORM\ManyToOne(targetEntity="Domain\Entities\Business\Company\Company", inversedBy="contract")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="company_id", referencedColumnName="id")
     * })
     */
    private $company;

    /**
     * @var \Domain\Entities\Business\Directory\Partner
     *
     * @ORM\ManyToOne(targetEntity="Domain\Entities\Business\Directory\Partner", inversedBy="contracts")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="partner_id", referencedColumnName="id")
     * })
     */
    private $partner;

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
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Domain\Entities\Services\Files", mappedBy="contracts")
     */
    private $files = array();

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Domain\Entities\Business\Objects\Specification", inversedBy="contracts")
     * @ORM\JoinTable(name="contracts_specification",
     *   joinColumns={
     *     @ORM\JoinColumn(name="contracts_id", referencedColumnName="id", onDelete="CASCADE")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="specification_id", referencedColumnName="id", onDelete="CASCADE")
     *   }
     * )
     */
    private $specifications = array();

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->invoices = new \Doctrine\Common\Collections\ArrayCollection();
        $this->files = new \Doctrine\Common\Collections\ArrayCollection();
        $this->specifications = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Contracts
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
     * Set number.
     *
     * @param string $number
     *
     * @return Contracts
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
     * Set numberSys.
     *
     * @param string|null $numberSys
     *
     * @return Contracts
     */
    public function setNumberSys($numberSys = null)
    {
        $this->numberSys = $numberSys;

        return $this;
    }

    /**
     * Get numberSys.
     *
     * @return string|null
     */
    public function getNumberSys()
    {
        return $this->numberSys;
    }

    /**
     * Set description.
     *
     * @param string|null $description
     *
     * @return Contracts
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
     * Set startAt.
     *
     * @param \DateTime $startAt
     *
     * @return Contracts
     */
    public function setStartAt($startAt)
    {
        $this->startAt = $startAt;

        return $this;
    }

    /**
     * Get startAt.
     *
     * @return \DateTime
     */
    public function getStartAt()
    {
        return $this->startAt;
    }

    /**
     * Set endAt.
     *
     * @param \DateTime|null $endAt
     *
     * @return Contracts
     */
    public function setEndAt($endAt = null)
    {
        $this->endAt = $endAt;

        return $this;
    }

    /**
     * Get endAt.
     *
     * @return \DateTime|null
     */
    public function getEndAt()
    {
        return $this->endAt;
    }

    /**
     * Set type.
     *
     * @param string $type
     *
     * @return Contracts
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type.
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set tax.
     *
     * @param int|null $tax
     *
     * @return Contracts
     */
    public function setTax($tax = null)
    {
        $this->tax = $tax;

        return $this;
    }

    /**
     * Get tax.
     *
     * @return int|null
     */
    public function getTax()
    {
        return $this->tax;
    }

    /**
     * Set taxAmount.
     *
     * @param float|null $taxAmount
     *
     * @return Contracts
     */
    public function setTaxAmount($taxAmount = null)
    {
        $this->taxAmount = $taxAmount;

        return $this;
    }

    /**
     * Get taxAmount.
     *
     * @return float|null
     */
    public function getTaxAmount()
    {
        return $this->taxAmount;
    }

    /**
     * Set amount.
     *
     * @param float|null $amount
     *
     * @return Contracts
     */
    public function setAmount($amount = null)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get amount.
     *
     * @return float|null
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Set credit.
     *
     * @param float|null $credit
     *
     * @return Contracts
     */
    public function setCredit($credit = null)
    {
        $this->credit = $credit;

        return $this;
    }

    /**
     * Get credit.
     *
     * @return float|null
     */
    public function getCredit()
    {
        return $this->credit;
    }

    /**
     * Set creditDays.
     *
     * @param int|null $creditDays
     *
     * @return Contracts
     */
    public function setCreditDays($creditDays = null)
    {
        $this->creditDays = $creditDays;

        return $this;
    }

    /**
     * Get creditDays.
     *
     * @return int|null
     */
    public function getCreditDays()
    {
        return $this->creditDays;
    }

    /**
     * Set createdAt.
     *
     * @param \DateTime $createdAt
     *
     * @return Contracts
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
     * @return Contracts
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
     * Set fixed.
     *
     * @param bool|null $fixed
     *
     * @return Contracts
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
     * Set active.
     *
     * @param bool|null $active
     *
     * @return Contracts
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
     * @return Contracts
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
     * @return Contracts
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
     * Add invoice.
     *
     * @param \Domain\Entities\Business\Document\Requisition\Invoice $invoice
     *
     * @return Contracts
     */
    public function addInvoice(\Domain\Entities\Business\Document\Requisition\Invoice $invoice)
    {
        $this->invoices[] = $invoice;

        return $this;
    }

    /**
     * Remove invoice.
     *
     * @param \Domain\Entities\Business\Document\Requisition\Invoice $invoice
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeInvoice(\Domain\Entities\Business\Document\Requisition\Invoice $invoice)
    {
        return $this->invoices->removeElement($invoice);
    }

    /**
     * Get invoices.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getInvoices()
    {
        return $this->invoices;
    }

    /**
     * Set company.
     *
     * @param \Domain\Entities\Business\Company\Company|null $company
     *
     * @return Contracts
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
     * Set partner.
     *
     * @param \Domain\Entities\Business\Directory\Partner|null $partner
     *
     * @return Contracts
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
     * Set autor.
     *
     * @param \Domain\Entities\Subscriber\Account|null $autor
     *
     * @return Contracts
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
     * Add file.
     *
     * @param \Domain\Entities\Services\Files $file
     *
     * @return Contracts
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
     * Add specification.
     *
     * @param \Domain\Entities\Business\Objects\Specification $specification
     *
     * @return Contracts
     */
    public function addSpecification(\Domain\Entities\Business\Objects\Specification $specification)
    {
        $this->specifications[] = $specification;

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
        return $this->specifications->removeElement($specification);
    }

    /**
     * Get specifications.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSpecifications()
    {
        return $this->specifications;
    }
}
