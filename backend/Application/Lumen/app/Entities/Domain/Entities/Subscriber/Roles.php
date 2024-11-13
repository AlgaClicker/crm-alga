<?php

namespace Domain\Entities\Subscriber;

/**
 * Roles
 */
class Roles
{
    /**
     * @var uuid
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string|null
     */
    private $service;

    /**
     * @var array|null
     */
    private $access;

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
     * @var array|null
     */
    private $autorId;

    /**
     * @var bool|null
     */
    private $active;

    /**
     * @var \DateTime|null
     */
    private $deletedAt;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $account;

    /**
     * @var \Domain\Entities\Business\Company\Company
     */
    private $company;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->account = new \Doctrine\Common\Collections\ArrayCollection();
    }

}
