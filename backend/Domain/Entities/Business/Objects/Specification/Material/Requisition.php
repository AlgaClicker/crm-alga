<?php

namespace Domain\Entities\Business\Objects\Specification\Material;

use Doctrine\ORM\Mapping as ORM;

/**
 * Requisition
 *
 * @ORM\Table(name="specification_material_requisition")
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
     * @var float
     *
     * @ORM\Column(name="amount", type="float", nullable=false)
     */
    private $amount;

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
     * @var string|null
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="draft", type="boolean", nullable=true, options={"default"="1"})
     */
    private $draft = true;

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
     * @var \Domain\Entities\Business\Objects\Specification
     *
     * @ORM\ManyToOne(targetEntity="Domain\Entities\Business\Objects\Specification")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="specification_id", referencedColumnName="id")
     * })
     */
    private $specification;

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
     *   @ORM\JoinColumn(name="autor", referencedColumnName="id")
     * })
     */
    private $autor;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Domain\Entities\Services\Files", mappedBy="specificationMaterialCalculation")
     */
    private $files = array();

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Domain\Entities\Business\Objects\SpecificationMaterial", mappedBy="SpecificationMaterialCalculation")
     */
    private $specificationMaterial = array();

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->files = new \Doctrine\Common\Collections\ArrayCollection();
        $this->specificationMaterial = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set amount.
     *
     * @param float $amount
     *
     * @return Requisition
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
     * Set active.
     *
     * @param bool|null $active
     *
     * @return Requisition
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
     * Set draft.
     *
     * @param bool|null $draft
     *
     * @return Requisition
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
     * Add file.
     *
     * @param \Domain\Entities\Services\Files $file
     *
     * @return Requisition
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
     * Add specificationMaterial.
     *
     * @param \Domain\Entities\Business\Objects\SpecificationMaterial $specificationMaterial
     *
     * @return Requisition
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
}
