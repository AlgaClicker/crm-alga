<?php

namespace Domain\Entities\Business\Directory;

/**
 * BankAccounts
 */
class BankAccounts
{
    /**
     * @var uuid
     */
    private $id;

    /**
     * @var string
     */
    private $account;

    /**
     * @var string|null
     */
    private $regulationAccountType;

    /**
     * @var string|null
     */
    private $ck;

    /**
     * @var int|null
     */
    private $accountCBRBIC;

    /**
     * @var \DateTime|null
     */
    private $dateIn;

    /**
     * @var \DateTime|null
     */
    private $accRstrDate;

    /**
     * @var string|null
     */
    private $accRstr;

    /**
     * @var string|null
     */
    private $accountStatus;

    /**
     * @var uuid|null
     */
    private $autorId;

    /**
     * @var bool|null
     */
    private $active;

    /**
     * @var bool|null
     */
    private $delete = false;

    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var \DateTime|null
     */
    private $updatedAt;

    /**
     * @var \Domain\Entities\Business\Directory\Bank
     */
    private $bank;


}
