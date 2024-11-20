<?php

namespace Domain\Entities\Business\Directory;

use Doctrine\ORM\Mapping as ORM;

/**
 * BankAccounts
 *
 * @ORM\Table(name="bank_accounts")
 * @ORM\Entity
 */
class BankAccounts
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
     * @var string
     *
     * @ORM\Column(name="account", type="text", nullable=false)
     */
    private $account;

    /**
     * @var string|null
     *
     * @ORM\Column(name="regulation_account_type", type="text", nullable=true)
     */
    private $regulationAccountType;

    /**
     * @var string|null
     *
     * @ORM\Column(name="ck", type="text", nullable=true)
     */
    private $ck;

    /**
     * @var int|null
     *
     * @ORM\Column(name="account_cbrbic", type="integer", nullable=true)
     */
    private $accountCBRBIC;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="date_in", type="date", nullable=true)
     */
    private $dateIn;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="acc_rs_date", type="date", nullable=true)
     */
    private $accRstrDate;

    /**
     * @var string|null
     *
     * @ORM\Column(name="acc_rstr", type="string", nullable=true)
     */
    private $accRstr;

    /**
     * @var string|null
     *
     * @ORM\Column(name="account_status", type="string", nullable=true)
     */
    private $accountStatus;

    /**
     * @var uuid|null
     *
     * @ORM\Column(name="autor_uuid", type="uuid", nullable=true)
     */
    private $autorId;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="active", type="boolean", nullable=true)
     */
    private $active;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="delete", type="boolean", nullable=true)
     */
    private $delete = false;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    private $createdAt;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @var \Domain\Entities\Business\Directory\Bank
     *
     * @ORM\ManyToOne(targetEntity="Domain\Entities\Business\Directory\Bank", inversedBy="bankAccounts")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="bank_id", referencedColumnName="id")
     * })
     */
    private $bank;


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
     * @param string $account
     *
     * @return BankAccounts
     */
    public function setAccount($account)
    {
        $this->account = $account;

        return $this;
    }

    /**
     * Get account.
     *
     * @return string
     */
    public function getAccount()
    {
        return $this->account;
    }

    /**
     * Set regulationAccountType.
     *
     * @param string|null $regulationAccountType
     *
     * @return BankAccounts
     */
    public function setRegulationAccountType($regulationAccountType = null)
    {
        $this->regulationAccountType = $regulationAccountType;

        return $this;
    }

    /**
     * Get regulationAccountType.
     *
     * @return string|null
     */
    public function getRegulationAccountType()
    {
        return $this->regulationAccountType;
    }

    /**
     * Set ck.
     *
     * @param string|null $ck
     *
     * @return BankAccounts
     */
    public function setCk($ck = null)
    {
        $this->ck = $ck;

        return $this;
    }

    /**
     * Get ck.
     *
     * @return string|null
     */
    public function getCk()
    {
        return $this->ck;
    }

    /**
     * Set accountCBRBIC.
     *
     * @param int|null $accountCBRBIC
     *
     * @return BankAccounts
     */
    public function setAccountCBRBIC($accountCBRBIC = null)
    {
        $this->accountCBRBIC = $accountCBRBIC;

        return $this;
    }

    /**
     * Get accountCBRBIC.
     *
     * @return int|null
     */
    public function getAccountCBRBIC()
    {
        return $this->accountCBRBIC;
    }

    /**
     * Set dateIn.
     *
     * @param \DateTime|null $dateIn
     *
     * @return BankAccounts
     */
    public function setDateIn($dateIn = null)
    {
        $this->dateIn = $dateIn;

        return $this;
    }

    /**
     * Get dateIn.
     *
     * @return \DateTime|null
     */
    public function getDateIn()
    {
        return $this->dateIn;
    }

    /**
     * Set accRstrDate.
     *
     * @param \DateTime|null $accRstrDate
     *
     * @return BankAccounts
     */
    public function setAccRstrDate($accRstrDate = null)
    {
        $this->accRstrDate = $accRstrDate;

        return $this;
    }

    /**
     * Get accRstrDate.
     *
     * @return \DateTime|null
     */
    public function getAccRstrDate()
    {
        return $this->accRstrDate;
    }

    /**
     * Set accRstr.
     *
     * @param string|null $accRstr
     *
     * @return BankAccounts
     */
    public function setAccRstr($accRstr = null)
    {
        $this->accRstr = $accRstr;

        return $this;
    }

    /**
     * Get accRstr.
     *
     * @return string|null
     */
    public function getAccRstr()
    {
        return $this->accRstr;
    }

    /**
     * Set accountStatus.
     *
     * @param string|null $accountStatus
     *
     * @return BankAccounts
     */
    public function setAccountStatus($accountStatus = null)
    {
        $this->accountStatus = $accountStatus;

        return $this;
    }

    /**
     * Get accountStatus.
     *
     * @return string|null
     */
    public function getAccountStatus()
    {
        return $this->accountStatus;
    }

    /**
     * Set autorId.
     *
     * @param uuid|null $autorId
     *
     * @return BankAccounts
     */
    public function setAutorId($autorId = null)
    {
        $this->autorId = $autorId;

        return $this;
    }

    /**
     * Get autorId.
     *
     * @return uuid|null
     */
    public function getAutorId()
    {
        return $this->autorId;
    }

    /**
     * Set active.
     *
     * @param bool|null $active
     *
     * @return BankAccounts
     */
    public function setActive($active = null)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get active.
     *
     * @return bool|null
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * Set delete.
     *
     * @param bool|null $delete
     *
     * @return BankAccounts
     */
    public function setDelete($delete = null)
    {
        $this->delete = $delete;

        return $this;
    }

    /**
     * Get delete.
     *
     * @return bool|null
     */
    public function getDelete()
    {
        return $this->delete;
    }

    /**
     * Set createdAt.
     *
     * @param \DateTime $createdAt
     *
     * @return BankAccounts
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt.
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt.
     *
     * @param \DateTime|null $updatedAt
     *
     * @return BankAccounts
     */
    public function setUpdatedAt($updatedAt = null)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt.
     *
     * @return \DateTime|null
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set bank.
     *
     * @param \Domain\Entities\Business\Directory\Bank|null $bank
     *
     * @return BankAccounts
     */
    public function setBank(\Domain\Entities\Business\Directory\Bank $bank = null)
    {
        $this->bank = $bank;

        return $this;
    }

    /**
     * Get bank.
     *
     * @return \Domain\Entities\Business\Directory\Bank|null
     */
    public function getBank()
    {
        return $this->bank;
    }
}
