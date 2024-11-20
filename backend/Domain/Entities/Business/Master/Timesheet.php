<?php

namespace Domain\Entities\Business\Master;

use Doctrine\ORM\Mapping as ORM;

/**
 * Timesheet
 *
 * @ORM\Table(name="timesheet")
 * @ORM\Entity
 */
class Timesheet
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
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="date", nullable=false)
     */
    private $date;

    /**
     * @var string|null
     *
     * @ORM\Column(name="time_amount", type="string", nullable=true)
     */
    private $timeAmount;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="lateness", type="boolean", nullable=true)
     */
    private $lateness;

    /**
     * @var int|null
     *
     * @ORM\Column(name="time_extra", type="integer", nullable=true)
     */
    private $timeExtra;

    /**
     * @var string|null
     *
     * @ORM\Column(name="comment", type="text", nullable=true)
     */
    private $comment;

    /**
     * @var string|null
     *
     * @ORM\Column(name="description", type="string", nullable=true)
     */
    private $description;

    /**
     * @var uuid|null
     *
     * @ORM\Column(name="autor_uuid", type="uuid", nullable=true)
     */
    private $autorId;

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
     * @var \DateTime|null
     *
     * @ORM\Column(name="deleted_at", type="datetime", nullable=true)
     */
    private $deletedAt;

    /**
     * @var \Domain\Entities\Business\Company\Workpeople
     *
     * @ORM\ManyToOne(targetEntity="Domain\Entities\Business\Company\Workpeople", inversedBy="timesheet")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="workperson_id", referencedColumnName="id")
     * })
     */
    private $workpeople;

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
     * Get id.
     *
     * @return uuid
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set date.
     *
     * @param \DateTime $date
     *
     * @return Timesheet
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date.
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set timeAmount.
     *
     * @param string|null $timeAmount
     *
     * @return Timesheet
     */
    public function setTimeAmount($timeAmount = null)
    {
        $this->timeAmount = $timeAmount;

        return $this;
    }

    /**
     * Get timeAmount.
     *
     * @return string|null
     */
    public function getTimeAmount()
    {
        return $this->timeAmount;
    }

    /**
     * Set lateness.
     *
     * @param bool|null $lateness
     *
     * @return Timesheet
     */
    public function setLateness($lateness = null)
    {
        $this->lateness = $lateness;

        return $this;
    }

    /**
     * Get lateness.
     *
     * @return bool|null
     */
    public function getLateness()
    {
        return $this->lateness;
    }

    /**
     * Set timeExtra.
     *
     * @param int|null $timeExtra
     *
     * @return Timesheet
     */
    public function setTimeExtra($timeExtra = null)
    {
        $this->timeExtra = $timeExtra;

        return $this;
    }

    /**
     * Get timeExtra.
     *
     * @return int|null
     */
    public function getTimeExtra()
    {
        return $this->timeExtra;
    }

    /**
     * Set comment.
     *
     * @param string|null $comment
     *
     * @return Timesheet
     */
    public function setComment($comment = null)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Get comment.
     *
     * @return string|null
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Set description.
     *
     * @param string|null $description
     *
     * @return Timesheet
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
     * Set autorId.
     *
     * @param uuid|null $autorId
     *
     * @return Timesheet
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
     * Set createdAt.
     *
     * @param \DateTime $createdAt
     *
     * @return Timesheet
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
     * @return Timesheet
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
     * Set active.
     *
     * @param bool|null $active
     *
     * @return Timesheet
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
     * @return Timesheet
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
     * @return Timesheet
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
     * Set workpeople.
     *
     * @param \Domain\Entities\Business\Company\Workpeople|null $workpeople
     *
     * @return Timesheet
     */
    public function setWorkpeople(\Domain\Entities\Business\Company\Workpeople $workpeople = null)
    {
        $this->workpeople = $workpeople;

        return $this;
    }

    /**
     * Get workpeople.
     *
     * @return \Domain\Entities\Business\Company\Workpeople|null
     */
    public function getWorkpeople()
    {
        return $this->workpeople;
    }

    /**
     * Set specification.
     *
     * @param \Domain\Entities\Business\Objects\Specification|null $specification
     *
     * @return Timesheet
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
