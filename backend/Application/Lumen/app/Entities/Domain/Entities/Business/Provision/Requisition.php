<?php

namespace Domain\Entities\Business\Provision;

/**
 * Requisition
 */
class Requisition
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
     * @var int
     */
    private $index;

    /**
     * @var string|null
     */
    private $status;

    /**
     * @var \DateTime
     */
    private $endAt;

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
    private $fixed = false;

    /**
     * @var string|null
     */
    private $description;

    /**
     * @var bool|null
     */
    private $delete = false;

    /**
     * @var \DateTime|null
     */
    private $deletedAt;


}
