<?php

namespace Domain\Entities\Business\Directory;

use Doctrine\ORM\Mapping as ORM;

/**
 * Contractor
 *
 * @ORM\Table(name="contractor")
 * @ORM\Entity
 */
class Contractor
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
     * @ORM\Column(name="full_name", type="text", nullable=true)
     */
    private $fullname;

    /**
     * @var string|null
     *
     * @ORM\Column(name="address", type="text", nullable=true)
     */
    private $address;

    /**
     * @var string|null
     *
     * @ORM\Column(name="type", type="string", nullable=true)
     */
    private $type;

    /**
     * @var int|null
     *
     * @ORM\Column(name="ogrn_date", type="bigint", nullable=true)
     */
    private $ogrnDate;

    /**
     * @var int|null
     *
     * @ORM\Column(name="ogrn", type="bigint", nullable=true)
     */
    private $ogrn;

    /**
     * @var int|null
     *
     * @ORM\Column(name="okpo", type="bigint", nullable=true)
     */
    private $okpo;

    /**
     * @var int|null
     *
     * @ORM\Column(name="oktmo", type="bigint", nullable=true)
     */
    private $oktmo;

    /**
     * @var int|null
     *
     * @ORM\Column(name="okogu", type="bigint", nullable=true)
     */
    private $okogu;

    /**
     * @var int|null
     *
     * @ORM\Column(name="inn", type="bigint", nullable=true)
     */
    private $inn;

    /**
     * @var int|null
     *
     * @ORM\Column(name="kpp", type="bigint", nullable=true)
     */
    private $kpp;

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
     * @var uuid|null
     *
     * @ORM\Column(name="autor_uuid", type="uuid", nullable=true)
     */
    private $autorId;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="active", type="boolean", nullable=true, options={"default"="1"})
     */
    private $active = true;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="delete", type="boolean", nullable=true)
     */
    private $delete = false;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Domain\Entities\Business\Directory\BankContractor", mappedBy="contractor")
     */
    private $bankAccounts;

    /**
     * @var \Domain\Entities\Business\Company\Company
     *
     * @ORM\ManyToOne(targetEntity="Domain\Entities\Business\Company\Company", inversedBy="contractor")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="company_id", referencedColumnName="id")
     * })
     */
    private $company;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->bankAccounts = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Contractor
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
     * @return Contractor
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
     * @return Contractor
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
     * Set type.
     *
     * @param string|null $type
     *
     * @return Contractor
     */
    public function setType($type = null)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type.
     *
     * @return string|null
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set ogrnDate.
     *
     * @param int|null $ogrnDate
     *
     * @return Contractor
     */
    public function setOgrnDate($ogrnDate = null)
    {
        $this->ogrnDate = $ogrnDate;

        return $this;
    }

    /**
     * Get ogrnDate.
     *
     * @return int|null
     */
    public function getOgrnDate()
    {
        return $this->ogrnDate;
    }

    /**
     * Set ogrn.
     *
     * @param int|null $ogrn
     *
     * @return Contractor
     */
    public function setOgrn($ogrn = null)
    {
        $this->ogrn = $ogrn;

        return $this;
    }

    /**
     * Get ogrn.
     *
     * @return int|null
     */
    public function getOgrn()
    {
        return $this->ogrn;
    }

    /**
     * Set okpo.
     *
     * @param int|null $okpo
     *
     * @return Contractor
     */
    public function setOkpo($okpo = null)
    {
        $this->okpo = $okpo;

        return $this;
    }

    /**
     * Get okpo.
     *
     * @return int|null
     */
    public function getOkpo()
    {
        return $this->okpo;
    }

    /**
     * Set oktmo.
     *
     * @param int|null $oktmo
     *
     * @return Contractor
     */
    public function setOktmo($oktmo = null)
    {
        $this->oktmo = $oktmo;

        return $this;
    }

    /**
     * Get oktmo.
     *
     * @return int|null
     */
    public function getOktmo()
    {
        return $this->oktmo;
    }

    /**
     * Set okogu.
     *
     * @param int|null $okogu
     *
     * @return Contractor
     */
    public function setOkogu($okogu = null)
    {
        $this->okogu = $okogu;

        return $this;
    }

    /**
     * Get okogu.
     *
     * @return int|null
     */
    public function getOkogu()
    {
        return $this->okogu;
    }

    /**
     * Set inn.
     *
     * @param int|null $inn
     *
     * @return Contractor
     */
    public function setInn($inn = null)
    {
        $this->inn = $inn;

        return $this;
    }

    /**
     * Get inn.
     *
     * @return int|null
     */
    public function getInn()
    {
        return $this->inn;
    }

    /**
     * Set kpp.
     *
     * @param int|null $kpp
     *
     * @return Contractor
     */
    public function setKpp($kpp = null)
    {
        $this->kpp = $kpp;

        return $this;
    }

    /**
     * Get kpp.
     *
     * @return int|null
     */
    public function getKpp()
    {
        return $this->kpp;
    }

    /**
     * Set createdAt.
     *
     * @param \DateTime $createdAt
     *
     * @return Contractor
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
     * @return Contractor
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
     * Set autorId.
     *
     * @param uuid|null $autorId
     *
     * @return Contractor
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
     * @return Contractor
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
     * @return Contractor
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
     * Add bankAccount.
     *
     * @param \Domain\Entities\Business\Directory\BankContractor $bankAccount
     *
     * @return Contractor
     */
    public function addBankAccount(\Domain\Entities\Business\Directory\BankContractor $bankAccount)
    {
        $this->bankAccounts[] = $bankAccount;

        return $this;
    }

    /**
     * Remove bankAccount.
     *
     * @param \Domain\Entities\Business\Directory\BankContractor $bankAccount
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeBankAccount(\Domain\Entities\Business\Directory\BankContractor $bankAccount)
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
     * Set company.
     *
     * @param \Domain\Entities\Business\Company\Company|null $company
     *
     * @return Contractor
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
}
