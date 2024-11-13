<?php

namespace Domain\Entities\Business\Directory;

/**
 * PartnerBankAccounts
 */
class PartnerBankAccounts
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
     * @var \DateTime|null
     */
    private $deletedAt;

    /**
     * @var \Domain\Entities\Business\Directory\Partner
     */
    private $partner;

    /**
     * @var \Domain\Entities\Business\Directory\Bank
     */
    private $bank;


}
