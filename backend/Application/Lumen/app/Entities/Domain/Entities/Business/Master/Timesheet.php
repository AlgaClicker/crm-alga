<?php

namespace Domain\Entities\Business\Master;

/**
 * Timesheet
 */
class Timesheet
{
    /**
     * @var uuid
     */
    private $id;

    /**
     * @var \DateTime
     */
    private $date;

    /**
     * @var string|null
     */
    private $timeAmount;

    /**
     * @var bool|null
     */
    private $lateness;

    /**
     * @var int|null
     */
    private $timeExtra;

    /**
     * @var string|null
     */
    private $comment;

    /**
     * @var string|null
     */
    private $description;

    /**
     * @var uuid|null
     */
    private $autorId;

    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var \DateTime|null
     */
    private $updatedAt;

    /**
     * @var bool|null
     */
    private $active;

    /**
     * @var bool|null
     */
    private $delete = false;

    /**
     * @var \DateTime|null
     */
    private $deletedAt;

    /**
     * @var \Domain\Entities\Business\Company\Workpeople
     */
    private $workpeople;

    /**
     * @var \Domain\Entities\Business\Objects\Specification
     */
    private $specification;


}
