<?php

namespace Domain\Entities\Business\Objects;

use Doctrine\ORM\Mapping as ORM;

/**
 * SpecificationTypeWorks
 *
 * @ORM\Table(name="specification_typeworks")
 * @ORM\Entity
 */
class SpecificationTypeWorks
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
     * @var string|null
     *
     * @ORM\Column(name="name_works", type="string", nullable=true)
     */
    private $nameWorks;

    /**
     * @var float|null
     *
     * @ORM\Column(name="quantity", type="float", nullable=true)
     */
    private $quantity;

    /**
     * @var string|null
     *
     * @ORM\Column(name="category", type="string", nullable=true)
     */
    private $category;

    /**
     * @var int|null
     *
     * @ORM\Column(name="level", type="integer", nullable=true)
     */
    private $level;

    /**
     * @var int|null
     *
     * @ORM\Column(name="indexcode", type="integer", nullable=true)
     */
    private $indexcode;

    /**
     * @var int|null
     *
     * @ORM\Column(name="idx", type="integer", nullable=true)
     */
    private $idx;

    /**
     * @var string|null
     *
     * @ORM\Column(name="NrCode", type="string", nullable=true)
     */
    private $NrCode;

    /**
     * @var string|null
     *
     * @ORM\Column(name="SpCode", type="string", nullable=true)
     */
    private $SpCode;

    /**
     * @var int|null
     *
     * @ORM\Column(name="Nacl", type="integer", nullable=true)
     */
    private $Nacl;

    /**
     * @var int|null
     *
     * @ORM\Column(name="Plan", type="integer", nullable=true)
     */
    private $Plan;

    /**
     * @var int|null
     *
     * @ORM\Column(name="NaclCurr", type="integer", nullable=true)
     */
    private $NaclCurr;

    /**
     * @var int|null
     *
     * @ORM\Column(name="PlanCurr", type="integer", nullable=true)
     */
    private $PlanCurr;

    /**
     * @var string|null
     *
     * @ORM\Column(name="NaclMask", type="string", nullable=true)
     */
    private $NaclMask;

    /**
     * @var string|null
     *
     * @ORM\Column(name="PlanMask", type="string", nullable=true)
     */
    private $PlanMask;

    /**
     * @var int|null
     *
     * @ORM\Column(name="type", type="integer", nullable=true)
     */
    private $type;

    /**
     * @var int|null
     *
     * @ORM\Column(name="parent", type="integer", nullable=true)
     */
    private $parent;

    /**
     * @var int|null
     *
     * @ORM\Column(name="group_id", type="integer", nullable=true)
     */
    private $groupId;

    /**
     * @var int|null
     *
     * @ORM\Column(name="group_name", type="integer", nullable=true)
     */
    private $groupName;

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
     * @ORM\Column(name="active", type="boolean", nullable=true, options={"default"="1"})
     */
    private $active = true;

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
     * @var \Domain\Entities\Business\Objects\Specification
     *
     * @ORM\ManyToOne(targetEntity="Domain\Entities\Business\Objects\Specification", inversedBy="specificationTypeworks")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="specification_id", referencedColumnName="id")
     * })
     */
    private $specification;

    /**
     * @var \Domain\Entities\Utils\Units
     *
     * @ORM\ManyToOne(targetEntity="Domain\Entities\Utils\Units", inversedBy="specificationTypeworks")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="unit_id", referencedColumnName="id")
     * })
     */
    private $unit;

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
     * @return SpecificationTypeWorks
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
     * Set nameWorks.
     *
     * @param string|null $nameWorks
     *
     * @return SpecificationTypeWorks
     */
    public function setNameWorks($nameWorks = null)
    {
        $this->nameWorks = $nameWorks;

        return $this;
    }

    /**
     * Get nameWorks.
     *
     * @return string|null
     */
    public function getNameWorks()
    {
        return $this->nameWorks;
    }

    /**
     * Set quantity.
     *
     * @param float|null $quantity
     *
     * @return SpecificationTypeWorks
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
     * Set category.
     *
     * @param string|null $category
     *
     * @return SpecificationTypeWorks
     */
    public function setCategory($category = null)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category.
     *
     * @return string|null
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set level.
     *
     * @param int|null $level
     *
     * @return SpecificationTypeWorks
     */
    public function setLevel($level = null)
    {
        $this->level = $level;

        return $this;
    }

    /**
     * Get level.
     *
     * @return int|null
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * Set indexcode.
     *
     * @param int|null $indexcode
     *
     * @return SpecificationTypeWorks
     */
    public function setIndexcode($indexcode = null)
    {
        $this->indexcode = $indexcode;

        return $this;
    }

    /**
     * Get indexcode.
     *
     * @return int|null
     */
    public function getIndexcode()
    {
        return $this->indexcode;
    }

    /**
     * Set idx.
     *
     * @param int|null $idx
     *
     * @return SpecificationTypeWorks
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
     * Set nrCode.
     *
     * @param string|null $nrCode
     *
     * @return SpecificationTypeWorks
     */
    public function setNrCode($nrCode = null)
    {
        $this->NrCode = $nrCode;

        return $this;
    }

    /**
     * Get nrCode.
     *
     * @return string|null
     */
    public function getNrCode()
    {
        return $this->NrCode;
    }

    /**
     * Set spCode.
     *
     * @param string|null $spCode
     *
     * @return SpecificationTypeWorks
     */
    public function setSpCode($spCode = null)
    {
        $this->SpCode = $spCode;

        return $this;
    }

    /**
     * Get spCode.
     *
     * @return string|null
     */
    public function getSpCode()
    {
        return $this->SpCode;
    }

    /**
     * Set nacl.
     *
     * @param int|null $nacl
     *
     * @return SpecificationTypeWorks
     */
    public function setNacl($nacl = null)
    {
        $this->Nacl = $nacl;

        return $this;
    }

    /**
     * Get nacl.
     *
     * @return int|null
     */
    public function getNacl()
    {
        return $this->Nacl;
    }

    /**
     * Set plan.
     *
     * @param int|null $plan
     *
     * @return SpecificationTypeWorks
     */
    public function setPlan($plan = null)
    {
        $this->Plan = $plan;

        return $this;
    }

    /**
     * Get plan.
     *
     * @return int|null
     */
    public function getPlan()
    {
        return $this->Plan;
    }

    /**
     * Set naclCurr.
     *
     * @param int|null $naclCurr
     *
     * @return SpecificationTypeWorks
     */
    public function setNaclCurr($naclCurr = null)
    {
        $this->NaclCurr = $naclCurr;

        return $this;
    }

    /**
     * Get naclCurr.
     *
     * @return int|null
     */
    public function getNaclCurr()
    {
        return $this->NaclCurr;
    }

    /**
     * Set planCurr.
     *
     * @param int|null $planCurr
     *
     * @return SpecificationTypeWorks
     */
    public function setPlanCurr($planCurr = null)
    {
        $this->PlanCurr = $planCurr;

        return $this;
    }

    /**
     * Get planCurr.
     *
     * @return int|null
     */
    public function getPlanCurr()
    {
        return $this->PlanCurr;
    }

    /**
     * Set naclMask.
     *
     * @param string|null $naclMask
     *
     * @return SpecificationTypeWorks
     */
    public function setNaclMask($naclMask = null)
    {
        $this->NaclMask = $naclMask;

        return $this;
    }

    /**
     * Get naclMask.
     *
     * @return string|null
     */
    public function getNaclMask()
    {
        return $this->NaclMask;
    }

    /**
     * Set planMask.
     *
     * @param string|null $planMask
     *
     * @return SpecificationTypeWorks
     */
    public function setPlanMask($planMask = null)
    {
        $this->PlanMask = $planMask;

        return $this;
    }

    /**
     * Get planMask.
     *
     * @return string|null
     */
    public function getPlanMask()
    {
        return $this->PlanMask;
    }

    /**
     * Set type.
     *
     * @param int|null $type
     *
     * @return SpecificationTypeWorks
     */
    public function setType($type = null)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type.
     *
     * @return int|null
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set parent.
     *
     * @param int|null $parent
     *
     * @return SpecificationTypeWorks
     */
    public function setParent($parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent.
     *
     * @return int|null
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Set groupId.
     *
     * @param int|null $groupId
     *
     * @return SpecificationTypeWorks
     */
    public function setGroupId($groupId = null)
    {
        $this->groupId = $groupId;

        return $this;
    }

    /**
     * Get groupId.
     *
     * @return int|null
     */
    public function getGroupId()
    {
        return $this->groupId;
    }

    /**
     * Set groupName.
     *
     * @param int|null $groupName
     *
     * @return SpecificationTypeWorks
     */
    public function setGroupName($groupName = null)
    {
        $this->groupName = $groupName;

        return $this;
    }

    /**
     * Get groupName.
     *
     * @return int|null
     */
    public function getGroupName()
    {
        return $this->groupName;
    }

    /**
     * Set createdAt.
     *
     * @param \DateTime $createdAt
     *
     * @return SpecificationTypeWorks
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
     * @return SpecificationTypeWorks
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
     * @return SpecificationTypeWorks
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
     * @return SpecificationTypeWorks
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
     * @return SpecificationTypeWorks
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
     * @return SpecificationTypeWorks
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
     * @return SpecificationTypeWorks
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
     * @return SpecificationTypeWorks
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
     * Set unit.
     *
     * @param \Domain\Entities\Utils\Units|null $unit
     *
     * @return SpecificationTypeWorks
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
     * Set autor.
     *
     * @param \Domain\Entities\Subscriber\Account|null $autor
     *
     * @return SpecificationTypeWorks
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
}
