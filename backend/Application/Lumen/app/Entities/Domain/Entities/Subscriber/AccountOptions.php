<?php

namespace Domain\Entities\Subscriber;

/**
 * AccountOptions
 */
class AccountOptions
{
    /**
     * @var uuid
     */
    private $id;

    /**
     * @var string
     */
    private $key;

    /**
     * @var string
     */
    private $val;

    /**
     * @var \DateTime|null
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
     * @var \Domain\Entities\Subscriber\Account
     */
    private $account;


}
