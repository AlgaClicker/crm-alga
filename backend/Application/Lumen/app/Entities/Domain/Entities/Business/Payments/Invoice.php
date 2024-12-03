<?php

namespace Domain\Entities\Business\Payments;

/**
 * Invoice
 */
class Invoice
{
    /**
     * @var uuid
     */
    private $id;

    /**
     * @var string|null
     */
    private $number;

    /**
     * @var \DateTime
     */
    private $date;

    /**
     * @var float|null
     */
    private $amount;

    /**
     * @var string
     */
    private $description;

    /**
     * @var string
     */
    private $type;

    /**
     * @var int|null
     */
    private $tax;

    /**
     * @var float|null
     */
    private $taxAmount;

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
     * @var \Domain\Entities\Subscriber\Account
     */
    private $autor;

    /**
     * @var \Domain\Entities\Business\Directory\Partner
     */
    private $partner;

    /**
     * @var \Domain\Entities\Business\Directory\PartnerBankAccounts
     */
    private $partnerBankAccount;

    /**
     * @var \Domain\Entities\Business\Objects\Specification
     */
    private $specification;

    /**
     * @var \Domain\Entities\Business\Company\Company
     */
    private $company;

    /**
     * @var \Domain\Entities\Business\Company\BankAccounts
     */
    private $companyBankAccount;


}
