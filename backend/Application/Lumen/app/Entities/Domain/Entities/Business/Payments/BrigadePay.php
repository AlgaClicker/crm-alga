<?php

namespace Domain\Entities\Business\Payments;

/**
 * BrigadePay
 */
class BrigadePay
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
     * @var float
     */
    private $amount;

    /**
     * @var string|null
     */
    private $description;

    /**
     * @var string
     */
    private $type;

    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var \DateTime|null
     */
    private $updatedAt;

    /**
     * @var \DateTime|null
     */
    private $deletedAt;

    /**
     * @var bool|null
     */
    private $draft = true;

    /**
     * @var bool|null
     */
    private $fixed = false;

    /**
     * @var \Domain\Entities\Business\Company\Company
     */
    private $company;

    /**
     * @var \Domain\Entities\Business\Master\Brigade
     */
    private $brigade;

    /**
     * @var \Domain\Entities\Subscriber\Account
     */
    private $autor;

    /**
     * @var \Domain\Entities\Business\Objects\Specification
     */
    private $specification;


}
