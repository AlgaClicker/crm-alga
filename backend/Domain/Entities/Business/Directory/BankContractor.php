<?php

namespace Domain\Entities\Business\Directory;

use Doctrine\ORM\Mapping as ORM;

/**
 * BankContractor
 *
 * @ORM\Table(name="contractor_bank")
 * @ORM\Entity
 */
class BankContractor
{
    /**
     * @var uuid
     *
     * @ORM\Column(name="id", type="uuid", options={"unsigned"=true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="CUSTOM")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="account", type="bigint", nullable=false)
     */
    private $account;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Domain\Entities\Business\Directory\Bank", mappedBy="bankContractor")
     */
    private $bank = array();

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->bank = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id.
     *
     * @return uuid
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set account.
     *
     * @param int $account
     *
     * @return BankContractor
     */
    public function setAccount($account)
    {
        $this->account = $account;

        return $this;
    }

    /**
     * Get account.
     *
     * @return int
     */
    public function getAccount()
    {
        return $this->account;
    }

    /**
     * Add bank.
     *
     * @param \Domain\Entities\Business\Directory\Bank $bank
     *
     * @return BankContractor
     */
    public function addBank(\Domain\Entities\Business\Directory\Bank $bank)
    {
        $this->bank[] = $bank;

        return $this;
    }

    /**
     * Remove bank.
     *
     * @param \Domain\Entities\Business\Directory\Bank $bank
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeBank(\Domain\Entities\Business\Directory\Bank $bank)
    {
        return $this->bank->removeElement($bank);
    }

    /**
     * Get bank.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getBank()
    {
        return $this->bank;
    }
}
