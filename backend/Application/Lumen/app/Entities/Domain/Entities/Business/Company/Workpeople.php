<?php

namespace Domain\Entities\Business\Company;

/**
 * Workpeople
 */
class Workpeople
{
    /**
     * @var uuid
     */
    private $id;

    /**
     * @var string|null
     */
    private $surname;

    /**
     * @var string|null
     */
    private $name;

    /**
     * @var string|null
     */
    private $tabelNumber;

    /**
     * @var string|null
     */
    private $patronymic;

    /**
     * @var string|null
     */
    private $phoneNumber;

    /**
     * @var string|null
     */
    private $inn;

    /**
     * @var string|null
     */
    private $snils;

    /**
     * @var string|null
     */
    private $position;

    /**
     * @var \DateTime|null
     */
    private $brithAt;

    /**
     * @var uuid|null
     */
    private $autorId;

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
    private $active = true;

    /**
     * @var bool|null
     */
    private $delete = false;

    /**
     * @var \DateTime|null
     */
    private $deletedAt;

    /**
     * @var \Domain\Entities\Subscriber\Account
     */
    private $account;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $timesheet;

    /**
     * @var \Domain\Entities\Business\Company\Company
     */
    private $company;

    /**
     * @var \Domain\Entities\Business\Directory\Profession
     */
    private $profession;

    /**
     * @var \Domain\Entities\Business\Master\Brigade
     */
    private $brigade;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->timesheet = new \Doctrine\Common\Collections\ArrayCollection();
    }

}
