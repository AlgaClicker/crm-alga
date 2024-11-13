<?php

namespace Domain\Entities\Business\Directory;

use Doctrine\ORM\Mapping as ORM;

/**
 * Bank
 *
 * @ORM\Table(name="banki", indexes={@ORM\Index(name="name_banki_index", columns={"name"}), @ORM\Index(name="bik_banki_index", columns={"bik"})})
 * @ORM\Entity
 */
class Bank
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
     * @ORM\Column(name="name", type="string", nullable=false)
     */
    private $name;

    /**
     * @var string|null
     *
     * @ORM\Column(name="fullname", type="text", nullable=true)
     */
    private $fullname;

    /**
     * @var string|null
     *
     * @ORM\Column(name="address", type="text", nullable=true)
     */
    private $address;

    /**
     * @var int|null
     *
     * @ORM\Column(name="bik", type="integer", nullable=true)
     */
    private $bik;

    /**
     * @var int|null
     *
     * @ORM\Column(name="payment_account", type="integer", nullable=true)
     */
    private $paymentAccount;

    /**
     * @var int|null
     *
     * @ORM\Column(name="corset", type="integer", nullable=true)
     */
    private $corset;

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
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Domain\Entities\Business\Directory\BankAccounts", mappedBy="bank")
     */
    private $bankAccounts;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Domain\Entities\Business\Directory\Recipient", inversedBy="bank")
     * @ORM\JoinTable(name="bank_recipient",
     *   joinColumns={
     *     @ORM\JoinColumn(name="bank_id", referencedColumnName="id", onDelete="CASCADE")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="recipient_id", referencedColumnName="id", onDelete="CASCADE")
     *   }
     * )
     */
    private $recipient = array();

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Domain\Entities\Business\Directory\BankContractor", inversedBy="bank")
     * @ORM\JoinTable(name="bank_bank_contractor",
     *   joinColumns={
     *     @ORM\JoinColumn(name="bank_id", referencedColumnName="id", onDelete="CASCADE")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="bank_contractor_id", referencedColumnName="id", onDelete="CASCADE")
     *   }
     * )
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
     * Set name.
     *
     * @param string $name
     *
     * @return Bank
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set fullname.
     *
     * @param string|null $fullname
     *
     * @return Bank
     */
    public function setFullname($fullname = null)
    {
        $this->fullname = $fullname;

        return $this;
    }

    /**
     * Get fullname.
     *
     * @return string|null
     */
    public function getFullname()
    {
        return $this->fullname;
    }

    /**
     * Set address.
     *
     * @param string|null $address
     *
     * @return Bank
     */
    public function setAddress($address = null)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address.
     *
     * @return string|null
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set bik.
     *
     * @param int|null $bik
     *
     * @return Bank
     */
    public function setBik($bik = null)
    {
        $this->bik = $bik;

        return $this;
    }

    /**
     * Get bik.
     *
     * @return int|null
     */
    public function getBik()
    {
        return $this->bik;
    }

    /**
     * Set paymentAccount.
     *
     * @param int|null $paymentAccount
     *
     * @return Bank
     */
    public function setPaymentAccount($paymentAccount = null)
    {
        $this->paymentAccount = $paymentAccount;

        return $this;
    }

    /**
     * Get paymentAccount.
     *
     * @return int|null
     */
    public function getPaymentAccount()
    {
        return $this->paymentAccount;
    }

    /**
     * Set corset.
     *
     * @param int|null $corset
     *
     * @return Bank
     */
    public function setCorset($corset = null)
    {
        $this->corset = $corset;

        return $this;
    }

    /**
     * Get corset.
     *
     * @return int|null
     */
    public function getCorset()
    {
        return $this->corset;
    }

    /**
     * Set autorId.
     *
     * @param uuid|null $autorId
     *
     * @return Bank
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
     * @return Bank
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
     * @return Bank
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
     * @return Bank
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
     * @return Bank
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
     * Add bankAccount.
     *
     * @param \Domain\Entities\Business\Directory\BankAccounts $bankAccount
     *
     * @return Bank
     */
    public function addBankAccount(\Domain\Entities\Business\Directory\BankAccounts $bankAccount)
    {
        $this->bankAccounts[] = $bankAccount;

        return $this;
    }

    /**
     * Remove bankAccount.
     *
     * @param \Domain\Entities\Business\Directory\BankAccounts $bankAccount
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeBankAccount(\Domain\Entities\Business\Directory\BankAccounts $bankAccount)
    {
        return $this->bankAccounts->removeElement($bankAccount);
    }

    /**
     * Get bankAccounts.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getBankAccounts()
    {
        return $this->bankAccounts;
    }

    /**
     * Add recipient.
     *
     * @param \Domain\Entities\Business\Directory\Recipient $recipient
     *
     * @return Bank
     */
    public function addRecipient(\Domain\Entities\Business\Directory\Recipient $recipient)
    {
        $this->recipient[] = $recipient;

        return $this;
    }

    /**
     * Remove recipient.
     *
     * @param \Domain\Entities\Business\Directory\Recipient $recipient
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeRecipient(\Domain\Entities\Business\Directory\Recipient $recipient)
    {
        return $this->recipient->removeElement($recipient);
    }

    /**
     * Get recipient.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRecipient()
    {
        return $this->recipient;
    }

    /**
     * Add bankContractor.
     *
     * @param \Domain\Entities\Business\Directory\BankContractor $bankContractor
     *
     * @return Bank
     */
    public function addBankContractor(\Domain\Entities\Business\Directory\BankContractor $bankContractor)
    {
        $this->bankContractor[] = $bankContractor;

        return $this;
    }

    /**
     * Remove bankContractor.
     *
     * @param \Domain\Entities\Business\Directory\BankContractor $bankContractor
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeBankContractor(\Domain\Entities\Business\Directory\BankContractor $bankContractor)
    {
        return $this->bankContractor->removeElement($bankContractor);
    }

    /**
     * Get bankContractor.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getBankContractor()
    {
        return $this->bankContractor;
    }
}
