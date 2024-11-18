<?php

namespace Domain\Entities\Business\Directory;

/**
 * Bank
 */
class Bank
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
     * @var int|null
     */
    private $bik;

    /**
     * @var int|null
     */
    private $paymentAccount;

    /**
     * @var int|null
     */
    private $corset;

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
     * @var \Doctrine\Common\Collections\Collection
     */
    private $bankAccounts;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $recipient = array();

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $bankContractor = array();

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->bankAccounts = new \Doctrine\Common\Collections\ArrayCollection();
        $this->recipient = new \Doctrine\Common\Collections\ArrayCollection();
        $this->bankContractor = new \Doctrine\Common\Collections\ArrayCollection();
    }

}
