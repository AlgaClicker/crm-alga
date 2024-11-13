<?php

namespace Domain\Entities\Business\Directory;

use Doctrine\ORM\Mapping as ORM;

/**
 * Partner
 *
 * @ORM\Table(name="partner")
 * @ORM\Entity
 */
class Partner
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
     * @var bool|null
     *
     * @ORM\Column(name="use_nds", type="boolean", nullable=true)
     */
    private $nds;

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
     * @var bool|null
     *
     * @ORM\Column(name="isGroup", type="boolean", nullable=true)
     */
    private $isGroup = false;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="deleted_at", type="datetime", nullable=true)
     */
    private $deletedAt;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Domain\Entities\Business\Directory\PartnerBankAccounts", mappedBy="partner")
     */
    private $bankAccounts;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Domain\Entities\Business\Directory\Partner", mappedBy="parent")
     * @ORM\OrderBy({
     *     "name"="ASC"
     * })
     */
    private $children;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Domain\Entities\Business\Document\Contracts", mappedBy="partner")
     */
    private $contracts;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Domain\Entities\Business\Document\Requisition\Invoice", mappedBy="partner")
     */
    private $requisitionInvoice;

    /**
     * @var \Domain\Entities\Business\Company\Company
     *
     * @ORM\ManyToOne(targetEntity="Domain\Entities\Business\Company\Company", inversedBy="partner")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="company_id", referencedColumnName="id")
     * })
     */
    private $company;

    /**
     * @var \Domain\Entities\Business\Directory\Partner
     *
     * @ORM\ManyToOne(targetEntity="Domain\Entities\Business\Directory\Partner", inversedBy="children")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     * })
     */
    private $parent;

    /**
     * @var \Domain\Entities\Subscriber\Account
     *
     * @ORM\ManyToOne(targetEntity="Domain\Entities\Subscriber\Account")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="autor", referencedColumnName="id")
     * })
     */
    private $autor;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->bankAccounts = new \Doctrine\Common\Collections\ArrayCollection();
        $this->children = new \Doctrine\Common\Collections\ArrayCollection();
        $this->contracts = new \Doctrine\Common\Collections\ArrayCollection();
        $this->requisitionInvoice = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Partner
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
     * @return Partner
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
     * @return Partner
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
     * @return Partner
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
     * @return Partner
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
     * @return Partner
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
     * @return Partner
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
     * Set nds.
     *
     * @param bool|null $nds
     *
     * @return Partner
     */
    public function setNds($nds = null)
    {
        $this->nds = $nds;

        return $this;
    }

    /**
     * Get nds.
     *
     * @return bool|null
     */
    public function getNds()
    {
        return $this->nds;
    }

    /**
     * Set oktmo.
     *
     * @param int|null $oktmo
     *
     * @return Partner
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
     * @return Partner
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
     * @return Partner
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
     * @return Partner
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
     * @return Partner
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
     * @return Partner
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
     * @return Partner
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
     * @return Partner
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
     * @return Partner
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
     * Set isGroup.
     *
     * @param bool|null $isGroup
     *
     * @return Partner
     */
    public function setIsGroup($isGroup = null)
    {
        $this->isGroup = $isGroup;

        return $this;
    }

    /**
     * Get isGroup.
     *
     * @return bool|null
     */
    public function getIsGroup()
    {
        return $this->isGroup;
    }

    /**
     * Set deletedAt.
     *
     * @param \DateTime|null $deletedAt
     *
     * @return Partner
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
     * Add bankAccount.
     *
     * @param \Domain\Entities\Business\Directory\PartnerBankAccounts $bankAccount
     *
     * @return Partner
     */
    public function addBankAccount(\Domain\Entities\Business\Directory\PartnerBankAccounts $bankAccount)
    {
        $this->bankAccounts[] = $bankAccount;

        return $this;
    }

    /**
     * Remove bankAccount.
     *
     * @param \Domain\Entities\Business\Directory\PartnerBankAccounts $bankAccount
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeBankAccount(\Domain\Entities\Business\Directory\PartnerBankAccounts $bankAccount)
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
     * Add child.
     *
     * @param \Domain\Entities\Business\Directory\Partner $child
     *
     * @return Partner
     */
    public function addChild(\Domain\Entities\Business\Directory\Partner $child)
    {
        $this->children[] = $child;

        return $this;
    }

    /**
     * Remove child.
     *
     * @param \Domain\Entities\Business\Directory\Partner $child
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeChild(\Domain\Entities\Business\Directory\Partner $child)
    {
        return $this->children->removeElement($child);
    }

    /**
     * Get children.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * Add contract.
     *
     * @param \Domain\Entities\Business\Document\Contracts $contract
     *
     * @return Partner
     */
    public function addContract(\Domain\Entities\Business\Document\Contracts $contract)
    {
        $this->contracts[] = $contract;

        return $this;
    }

    /**
     * Remove contract.
     *
     * @param \Domain\Entities\Business\Document\Contracts $contract
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeContract(\Domain\Entities\Business\Document\Contracts $contract)
    {
        return $this->contracts->removeElement($contract);
    }

    /**
     * Get contracts.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getContracts()
    {
        return $this->contracts;
    }

    /**
     * Add requisitionInvoice.
     *
     * @param \Domain\Entities\Business\Document\Requisition\Invoice $requisitionInvoice
     *
     * @return Partner
     */
    public function addRequisitionInvoice(\Domain\Entities\Business\Document\Requisition\Invoice $requisitionInvoice)
    {
        $this->requisitionInvoice[] = $requisitionInvoice;

        return $this;
    }

    /**
     * Remove requisitionInvoice.
     *
     * @param \Domain\Entities\Business\Document\Requisition\Invoice $requisitionInvoice
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeRequisitionInvoice(\Domain\Entities\Business\Document\Requisition\Invoice $requisitionInvoice)
    {
        return $this->requisitionInvoice->removeElement($requisitionInvoice);
    }

    /**
     * Get requisitionInvoice.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRequisitionInvoice()
    {
        return $this->requisitionInvoice;
    }

    /**
     * Set company.
     *
     * @param \Domain\Entities\Business\Company\Company|null $company
     *
     * @return Partner
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
     * Set parent.
     *
     * @param \Domain\Entities\Business\Directory\Partner|null $parent
     *
     * @return Partner
     */
    public function setParent(\Domain\Entities\Business\Directory\Partner $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent.
     *
     * @return \Domain\Entities\Business\Directory\Partner|null
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Set autor.
     *
     * @param \Domain\Entities\Subscriber\Account|null $autor
     *
     * @return Partner
     */
    public function setAutor(\Domain\Entities\Subscriber\Account $autor = null)
    {
        $this->autor = $autor;

        return $this;
    }

    /**
     * Get autor.
     *
     * @return \Domain\Entities\Subscriber\Account|null
     */
    public function getAutor()
    {
        return $this->autor;
    }
}
