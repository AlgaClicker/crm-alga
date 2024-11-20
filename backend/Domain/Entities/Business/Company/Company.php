<?php

namespace Domain\Entities\Business\Company;

use Doctrine\ORM\Mapping as ORM;

/**
 * Company
 *
 * @ORM\Table(name="company")
 * @ORM\Entity
 */
class Company
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
     * @var \DateTime|null
     *
     * @ORM\Column(name="deleted_at", type="datetime", nullable=true)
     */
    private $deletedAt;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Domain\Entities\Business\Objects\Objects", mappedBy="company")
     */
    private $objects;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Domain\Entities\Subscriber\Account", mappedBy="company")
     */
    private $accounts;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Domain\Entities\Business\Directory\Material", mappedBy="company")
     */
    private $material;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Domain\Entities\Business\Document\Workflow", mappedBy="company")
     */
    private $workflow;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Domain\Entities\Business\Directory\Recipient", mappedBy="company")
     */
    private $recipient;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Domain\Entities\Business\Company\BankAccounts", mappedBy="company")
     */
    private $bankAccounts;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Domain\Entities\Business\Directory\Partner", mappedBy="company")
     */
    private $partner;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Domain\Entities\Business\Document\Contracts", mappedBy="company")
     */
    private $contract;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Domain\Entities\Business\Master\Brigade", mappedBy="company")
     */
    private $brigades;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Domain\Entities\Business\Company\Workpeople", mappedBy="company")
     */
    private $workpeople;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Domain\Entities\Business\Document\Tasks", mappedBy="company")
     */
    private $tasks;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Domain\Entities\Subscriber\Roles", mappedBy="company")
     */
    private $roles;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Domain\Entities\Business\Document\BrigadeSpecification", mappedBy="company")
     */
    private $brigadeSpecification;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->objects = new \Doctrine\Common\Collections\ArrayCollection();
        $this->accounts = new \Doctrine\Common\Collections\ArrayCollection();
        $this->material = new \Doctrine\Common\Collections\ArrayCollection();
        $this->workflow = new \Doctrine\Common\Collections\ArrayCollection();
        $this->recipient = new \Doctrine\Common\Collections\ArrayCollection();
        $this->bankAccounts = new \Doctrine\Common\Collections\ArrayCollection();
        $this->partner = new \Doctrine\Common\Collections\ArrayCollection();
        $this->contract = new \Doctrine\Common\Collections\ArrayCollection();
        $this->brigades = new \Doctrine\Common\Collections\ArrayCollection();
        $this->workpeople = new \Doctrine\Common\Collections\ArrayCollection();
        $this->tasks = new \Doctrine\Common\Collections\ArrayCollection();
        $this->roles = new \Doctrine\Common\Collections\ArrayCollection();
        $this->brigadeSpecification = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Company
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
     * @return Company
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
     * @return Company
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
     * @return Company
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
     * @return Company
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
     * @return Company
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
     * @return Company
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
     * @return Company
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
     * @return Company
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
     * @return Company
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
     * @return Company
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
     * @return Company
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
     * @return Company
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
     * @return Company
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
     * @return Company
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
     * @return Company
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
     * Set deletedAt.
     *
     * @param \DateTime|null $deletedAt
     *
     * @return Company
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
     * Add object.
     *
     * @param \Domain\Entities\Business\Objects\Objects $object
     *
     * @return Company
     */
    public function addObject(\Domain\Entities\Business\Objects\Objects $object)
    {
        $this->objects[] = $object;

        return $this;
    }

    /**
     * Remove object.
     *
     * @param \Domain\Entities\Business\Objects\Objects $object
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeObject(\Domain\Entities\Business\Objects\Objects $object)
    {
        return $this->objects->removeElement($object);
    }

    /**
     * Get objects.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getObjects()
    {
        return $this->objects;
    }

    /**
     * Add account.
     *
     * @param \Domain\Entities\Subscriber\Account $account
     *
     * @return Company
     */
    public function addAccount(\Domain\Entities\Subscriber\Account $account)
    {
        $this->accounts[] = $account;

        return $this;
    }

    /**
     * Remove account.
     *
     * @param \Domain\Entities\Subscriber\Account $account
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeAccount(\Domain\Entities\Subscriber\Account $account)
    {
        return $this->accounts->removeElement($account);
    }

    /**
     * Get accounts.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAccounts()
    {
        return $this->accounts;
    }

    /**
     * Add material.
     *
     * @param \Domain\Entities\Business\Directory\Material $material
     *
     * @return Company
     */
    public function addMaterial(\Domain\Entities\Business\Directory\Material $material)
    {
        $this->material[] = $material;

        return $this;
    }

    /**
     * Remove material.
     *
     * @param \Domain\Entities\Business\Directory\Material $material
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeMaterial(\Domain\Entities\Business\Directory\Material $material)
    {
        return $this->material->removeElement($material);
    }

    /**
     * Get material.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMaterial()
    {
        return $this->material;
    }

    /**
     * Add workflow.
     *
     * @param \Domain\Entities\Business\Document\Workflow $workflow
     *
     * @return Company
     */
    public function addWorkflow(\Domain\Entities\Business\Document\Workflow $workflow)
    {
        $this->workflow[] = $workflow;

        return $this;
    }

    /**
     * Remove workflow.
     *
     * @param \Domain\Entities\Business\Document\Workflow $workflow
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeWorkflow(\Domain\Entities\Business\Document\Workflow $workflow)
    {
        return $this->workflow->removeElement($workflow);
    }

    /**
     * Get workflow.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getWorkflow()
    {
        return $this->workflow;
    }

    /**
     * Add recipient.
     *
     * @param \Domain\Entities\Business\Directory\Recipient $recipient
     *
     * @return Company
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
     * Add bankAccount.
     *
     * @param \Domain\Entities\Business\Company\BankAccounts $bankAccount
     *
     * @return Company
     */
    public function addBankAccount(\Domain\Entities\Business\Company\BankAccounts $bankAccount)
    {
        $this->bankAccounts[] = $bankAccount;

        return $this;
    }

    /**
     * Remove bankAccount.
     *
     * @param \Domain\Entities\Business\Company\BankAccounts $bankAccount
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeBankAccount(\Domain\Entities\Business\Company\BankAccounts $bankAccount)
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
     * Add partner.
     *
     * @param \Domain\Entities\Business\Directory\Partner $partner
     *
     * @return Company
     */
    public function addPartner(\Domain\Entities\Business\Directory\Partner $partner)
    {
        $this->partner[] = $partner;

        return $this;
    }

    /**
     * Remove partner.
     *
     * @param \Domain\Entities\Business\Directory\Partner $partner
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removePartner(\Domain\Entities\Business\Directory\Partner $partner)
    {
        return $this->partner->removeElement($partner);
    }

    /**
     * Get partner.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPartner()
    {
        return $this->partner;
    }

    /**
     * Add contract.
     *
     * @param \Domain\Entities\Business\Document\Contracts $contract
     *
     * @return Company
     */
    public function addContract(\Domain\Entities\Business\Document\Contracts $contract)
    {
        $this->contract[] = $contract;

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
        return $this->contract->removeElement($contract);
    }

    /**
     * Get contract.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getContract()
    {
        return $this->contract;
    }

    /**
     * Add brigade.
     *
     * @param \Domain\Entities\Business\Master\Brigade $brigade
     *
     * @return Company
     */
    public function addBrigade(\Domain\Entities\Business\Master\Brigade $brigade)
    {
        $this->brigades[] = $brigade;

        return $this;
    }

    /**
     * Remove brigade.
     *
     * @param \Domain\Entities\Business\Master\Brigade $brigade
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeBrigade(\Domain\Entities\Business\Master\Brigade $brigade)
    {
        return $this->brigades->removeElement($brigade);
    }

    /**
     * Get brigades.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getBrigades()
    {
        return $this->brigades;
    }

    /**
     * Add workperson.
     *
     * @param \Domain\Entities\Business\Company\Workpeople $workperson
     *
     * @return Company
     */
    public function addWorkperson(\Domain\Entities\Business\Company\Workpeople $workperson)
    {
        $this->workpeople[] = $workperson;

        return $this;
    }

    /**
     * Remove workperson.
     *
     * @param \Domain\Entities\Business\Company\Workpeople $workperson
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeWorkperson(\Domain\Entities\Business\Company\Workpeople $workperson)
    {
        return $this->workpeople->removeElement($workperson);
    }

    /**
     * Get workpeople.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getWorkpeople()
    {
        return $this->workpeople;
    }

    /**
     * Add task.
     *
     * @param \Domain\Entities\Business\Document\Tasks $task
     *
     * @return Company
     */
    public function addTask(\Domain\Entities\Business\Document\Tasks $task)
    {
        $this->tasks[] = $task;

        return $this;
    }

    /**
     * Remove task.
     *
     * @param \Domain\Entities\Business\Document\Tasks $task
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeTask(\Domain\Entities\Business\Document\Tasks $task)
    {
        return $this->tasks->removeElement($task);
    }

    /**
     * Get tasks.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTasks()
    {
        return $this->tasks;
    }

    /**
     * Add role.
     *
     * @param \Domain\Entities\Subscriber\Roles $role
     *
     * @return Company
     */
    public function addRole(\Domain\Entities\Subscriber\Roles $role)
    {
        $this->roles[] = $role;

        return $this;
    }

    /**
     * Remove role.
     *
     * @param \Domain\Entities\Subscriber\Roles $role
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeRole(\Domain\Entities\Subscriber\Roles $role)
    {
        return $this->roles->removeElement($role);
    }

    /**
     * Get roles.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * Add brigadeSpecification.
     *
     * @param \Domain\Entities\Business\Document\BrigadeSpecification $brigadeSpecification
     *
     * @return Company
     */
    public function addBrigadeSpecification(\Domain\Entities\Business\Document\BrigadeSpecification $brigadeSpecification)
    {
        $this->brigadeSpecification[] = $brigadeSpecification;

        return $this;
    }

    /**
     * Remove brigadeSpecification.
     *
     * @param \Domain\Entities\Business\Document\BrigadeSpecification $brigadeSpecification
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeBrigadeSpecification(\Domain\Entities\Business\Document\BrigadeSpecification $brigadeSpecification)
    {
        return $this->brigadeSpecification->removeElement($brigadeSpecification);
    }

    /**
     * Get brigadeSpecification.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getBrigadeSpecification()
    {
        return $this->brigadeSpecification;
    }
}
