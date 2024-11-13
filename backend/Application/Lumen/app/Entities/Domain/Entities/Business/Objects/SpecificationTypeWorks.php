<?php

namespace Domain\Entities\Business\Objects;

/**
 * SpecificationTypeWorks
 */
class SpecificationTypeWorks
{
    /**
     * @var uuid
     */
    private $id;

    /**
     * @var string
     */
    private $number;

    /**
     * @var string|null
     */
    private $nameWorks;

    /**
     * @var float|null
     */
    private $quantity;

    /**
     * @var string|null
     */
    private $category;

    /**
     * @var int|null
     */
    private $level;

    /**
     * @var int|null
     */
    private $indexcode;

    /**
     * @var int|null
     */
    private $idx;

    /**
     * @var string|null
     */
    private $NrCode;

    /**
     * @var string|null
     */
    private $SpCode;

    /**
     * @var int|null
     */
    private $Nacl;

    /**
     * @var int|null
     */
    private $Plan;

    /**
     * @var int|null
     */
    private $NaclCurr;

    /**
     * @var int|null
     */
    private $PlanCurr;

    /**
     * @var string|null
     */
    private $NaclMask;

    /**
     * @var string|null
     */
    private $PlanMask;

    /**
     * @var int|null
     */
    private $type;

    /**
     * @var int|null
     */
    private $parent;

    /**
     * @var int|null
     */
    private $groupId;

    /**
     * @var int|null
     */
    private $groupName;

    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var \DateTime|null
     */
    private $updatedAt;

    /**
     * @var uuid|null
     */
    private $autorId;

    /**
     * @var bool|null
     */
    private $active = true;

    /**
     * @var bool|null
     */
    private $draft;

    /**
     * @var bool|null
     */
    private $delete = false;

    /**
     * @var \DateTime|null
     */
    private $deletedAt;

    /**
     * @var \Domain\Entities\Business\Objects\Specification
     */
    private $specification;

    /**
     * @var \Domain\Entities\Utils\Units
     */
    private $unit;

    /**
     * @var \Domain\Entities\Subscriber\Account
     */
    private $autor;


}
