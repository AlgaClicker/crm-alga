<?php

namespace Domain\Entities\Subscriber;

/**
 * AccountTokens
 */
class AccountTokens
{
    /**
     * @var uuid
     */
    private $id;

    /**
     * @var string|null
     */
    private $token;

    /**
     * @var string|null
     */
    private $device;

    /**
     * @var string|null
     */
    private $client;

    /**
     * @var string|null
     */
    private $host;

    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var int|null
     */
    private $expiresIn;

    /**
     * @var \DateTime|null
     */
    private $updatedAt;

    /**
     * @var \Domain\Entities\Subscriber\Account
     */
    private $account;


}
