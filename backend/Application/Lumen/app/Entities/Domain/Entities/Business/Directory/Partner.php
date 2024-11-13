<?php

namespace Domain\Entities\Business\Directory;

/**
 * Partner
 */
class Partner
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
     * @var bool|null
     */
    private $nds;

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
     * @var bool|null
     */
    private $isGroup = false;

    /**
     * @var \DateTime|null
     */
    private $deletedAt;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $bankAccounts;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $children;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $contracts;

    /**
     * @var \Domain\Entities\Business\Company\Company
     */
    private $company;

    /**
     * @var \Domain\Entities\Business\Directory\Partner
     */
    private $parent;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->bankAccounts = new \Doctrine\Common\Collections\ArrayCollection();
        $this->children = new \Doctrine\Common\Collections\ArrayCollection();
        $this->contracts = new \Doctrine\Common\Collections\ArrayCollection();
    }

}
