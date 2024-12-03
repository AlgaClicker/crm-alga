<?php

namespace Domain\Entities\Business\Company;

use Doctrine\ORM\Mapping as ORM;

/**
 * BankAccounts
 *
 * @ORM\Table(name="compnay_bank_accounts")
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
     * @ORM\Column(name="account", type="string", nullable=false)
     */
    private $account;

    /**
     * @var \Domain\Entities\Business\Company\Company
     *
     * @ORM\ManyToOne(targetEntity="Domain\Entities\Business\Company\Company", inversedBy="bankAccounts")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="company_id", referencedColumnName="id")
     * })
     */
    private $company;

    /**
     * @var \Domain\Entities\Business\Directory\Bank
     *
     * @ORM\ManyToOne(targetEntity="Domain\Entities\Business\Directory\Bank")
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
     * Set company.
     *
     * @param \Domain\Entities\Business\Company\Company|null $company
     *
     * @return BankAccounts
     */
    public function setCompany(\Domain\Entities\Business\Company\Company $company = null)
    {
        $this->company = $company;

        return $this;
    }

    /**
     * Get company.
     *
     * @return \Domain\Entities\Business\Company\Company|null
     */
    public function getCompany()
    {
        return $this->company;
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
