<?php

namespace Domain\Entities\Business\Company;

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
     * @var \Domain\Entities\Business\Company\Company
     */
    private $company;

    /**
     * @var \Domain\Entities\Business\Directory\Bank
     */
    private $bank;


}
