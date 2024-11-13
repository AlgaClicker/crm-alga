<?php

namespace Domain\Entities\Business\Directory;

use Doctrine\ORM\Mapping as ORM;

/**
 * PartnerBankAccounts
 *
 * @ORM\Table(name="partner_bank_accounts")
 * @ORM\Entity
 */
class PartnerBankAccounts
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
     * @var \DateTime|null
     *
     * @ORM\Column(name="deleted_at", type="datetime", nullable=true)
     */
    private $deletedAt;

    /**
     * @var \Domain\Entities\Business\Directory\Partner
     *
     * @ORM\ManyToOne(targetEntity="Domain\Entities\Business\Directory\Partner", inversedBy="bankAccounts")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="partner_id", referencedColumnName="id")
     * })
     */
    private $partner;

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
     * @return PartnerBankAccounts
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
     * Set delete.
     *
     * @param bool|null $delete
     *
     * @return PartnerBankAccounts
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
     * @return PartnerBankAccounts
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
     * @return PartnerBankAccounts
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
     * Set deletedAt.
     *
     * @param \DateTime|null $deletedAt
     *
     * @return PartnerBankAccounts
     */
    public function setDeletedAt($deletedAt = null)
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }

    /**
     * Get deletedAt.
     *
     * @return \DateTime|null
     */
    public function getDeletedAt()
    {
        return $this->deletedAt;
    }

    /**
     * Set partner.
     *
     * @param \Domain\Entities\Business\Directory\Partner|null $partner
     *
     * @return PartnerBankAccounts
     */
    public function setPartner(\Domain\Entities\Business\Directory\Partner $partner = null)
    {
        $this->partner = $partner;

        return $this;
    }

    /**
     * Get partner.
     *
     * @return \Domain\Entities\Business\Directory\Partner|null
     */
    public function getPartner()
    {
        return $this->partner;
    }

    /**
     * Set bank.
     *
     * @param \Domain\Entities\Business\Directory\Bank|null $bank
     *
     * @return PartnerBankAccounts
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
