<?php

namespace Domain\Entities\Subscriber;

/**
 * Account
 */
class Account
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
     * @var string
     */
    private $username;

    /**
     * @var string|null
     */
    private $patronymic;

    /**
     * @var int|null
     */
    private $phoneNumber;

    /**
     * @var string|null
     */
    private $token;

    /**
     * @var string
     */
    private $email;

    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var \DateTime|null
     */
    private $lastLogin;

    /**
     * @var \DateTime|null
     */
    private $updatedAt;

    /**
     * @var \stdClass|null
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
     * @var bool|null
     */
    private $isAdmin = false;

    /**
     * @var \Domain\Entities\Business\Company\Workpeople
     */
    private $workpeople;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $accountoptions;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $accountTokens;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $brigades;

    /**
     * @var \Domain\Entities\Business\Company\Company
     */
    private $company;

    /**
     * @var \Domain\Entities\Subscriber\Roles
     */
    private $roles;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $objects = array();

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $workflows = array();

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $tasks = array();

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->accountoptions = new \Doctrine\Common\Collections\ArrayCollection();
        $this->accountTokens = new \Doctrine\Common\Collections\ArrayCollection();
        $this->brigades = new \Doctrine\Common\Collections\ArrayCollection();
        $this->objects = new \Doctrine\Common\Collections\ArrayCollection();
        $this->workflows = new \Doctrine\Common\Collections\ArrayCollection();
        $this->tasks = new \Doctrine\Common\Collections\ArrayCollection();
    }

}
