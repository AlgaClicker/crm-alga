<?php

namespace Domain\Entities\Business\Company;

/**
 * Company
 */
class Company
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
    private $fullname;

    /**
     * @var string|null
     */
    private $address;

    /**
     * @var string|null
     */
    private $type;

    /**
     * @var int|null
     */
    private $ogrnDate;

    /**
     * @var int|null
     */
    private $ogrn;

    /**
     * @var int|null
     */
    private $okpo;

    /**
     * @var int|null
     */
    private $oktmo;

    /**
     * @var int|null
     */
    private $okogu;

    /**
     * @var int|null
     */
    private $inn;

    /**
     * @var int|null
     */
    private $kpp;

    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var \DateTime|null
     */
    private $updatedAt;

    /**
     * @var uuid|null
     */
    private $autorId;

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
     * @var \Doctrine\Common\Collections\Collection
     */
    private $objects;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $accounts;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $materials;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $workflow;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $recipient;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $bankAccounts;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $partner;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $contract;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $brigades;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $workpeople;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $tasks;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $roles;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $contracts = array();

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->objects = new \Doctrine\Common\Collections\ArrayCollection();
        $this->accounts = new \Doctrine\Common\Collections\ArrayCollection();
        $this->materials = new \Doctrine\Common\Collections\ArrayCollection();
        $this->workflow = new \Doctrine\Common\Collections\ArrayCollection();
        $this->recipient = new \Doctrine\Common\Collections\ArrayCollection();
        $this->bankAccounts = new \Doctrine\Common\Collections\ArrayCollection();
        $this->partner = new \Doctrine\Common\Collections\ArrayCollection();
        $this->contract = new \Doctrine\Common\Collections\ArrayCollection();
        $this->brigades = new \Doctrine\Common\Collections\ArrayCollection();
        $this->workpeople = new \Doctrine\Common\Collections\ArrayCollection();
        $this->tasks = new \Doctrine\Common\Collections\ArrayCollection();
        $this->roles = new \Doctrine\Common\Collections\ArrayCollection();
        $this->contracts = new \Doctrine\Common\Collections\ArrayCollection();
    }

}
