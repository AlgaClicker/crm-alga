<?php

namespace Domain\Entities\Business\Master;

use Doctrine\ORM\Mapping as ORM;

/**
 * Requisition
 *
 * @ORM\Table(name="master_requisition")
 * @ORM\Entity
 */
class Requisition
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
     * @ORM\Column(name="type", type="string", nullable=true)
     */
    private $type;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="end_at", type="datetime", nullable=false)
     */
    private $endAt;

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
     * @var string|null
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

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
     * @ORM\OneToMany(targetEntity="Domain\Entities\Business\Master\RequisitionMaterials", mappedBy="requisition")
     * @ORM\OrderBy({
     *     "createdAt"="ASC"
     * })
     */
    private $materials;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Domain\Entities\Business\Document\Requisition\Invoice", mappedBy="requisition")
     */
    private $invoices;

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
     * @var \Domain\Entities\Business\Objects\Specification
     *
     * @ORM\ManyToOne(targetEntity="Domain\Entities\Business\Objects\Specification", inversedBy="requisitions", cascade={"persist","merge"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="specification_id", referencedColumnName="id")
     * })
     */
    private $specification;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->materials = new \Doctrine\Common\Collections\ArrayCollection();
        $this->invoices = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Requisition
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
     * @return Requisition
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
     * @return Requisition
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
     * Set type.
     *
     * @param string|null $type
     *
     * @return Requisition
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
     * Set endAt.
     *
     * @param \DateTime $endAt
     *
     * @return Requisition
     */
    public function setEndAt($endAt)
    {
        $this->endAt = $endAt;

        return $this;
    }

    /**
     * Get endAt.
     *
     * @return \DateTime
     */
    public function getEndAt()
    {
        return $this->endAt;
    }

    /**
     * Set createdAt.
     *
     * @param \DateTime $createdAt
     *
     * @return Requisition
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
     * @return Requisition
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
     * @return Requisition
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
     * Set description.
     *
     * @param string|null $description
     *
     * @return Requisition
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
     * Set delete.
     *
     * @param bool|null $delete
     *
     * @return Requisition
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
     * @return Requisition
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
     * Add material.
     *
     * @param \Domain\Entities\Business\Master\RequisitionMaterials $material
     *
     * @return Requisition
     */
    public function addMaterial(\Domain\Entities\Business\Master\RequisitionMaterials $material)
    {
        $this->materials[] = $material;

        return $this;
    }

    /**
     * Remove material.
     *
     * @param \Domain\Entities\Business\Master\RequisitionMaterials $material
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeMaterial(\Domain\Entities\Business\Master\RequisitionMaterials $material)
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
     * Add invoice.
     *
     * @param \Domain\Entities\Business\Document\Requisition\Invoice $invoice
     *
     * @return Requisition
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
     * @return Requisition
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
     * @return Requisition
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
     * @return Requisition
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
     * Set specification.
     *
     * @param \Domain\Entities\Business\Objects\Specification|null $specification
     *
     * @return Requisition
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
}
