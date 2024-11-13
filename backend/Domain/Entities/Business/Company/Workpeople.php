<?php

namespace Domain\Entities\Business\Company;

use Doctrine\ORM\Mapping as ORM;

/**
 * Workpeople
 *
 * @ORM\Table(name="workpeople")
 * @ORM\Entity
 */
class Workpeople
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
     * @var string|null
     *
     * @ORM\Column(name="surname", type="string", nullable=true)
     */
    private $surname;

    /**
     * @var string|null
     *
     * @ORM\Column(name="name", type="string", nullable=true)
     */
    private $name;

    /**
     * @var string|null
     *
     * @ORM\Column(name="tabel_number", type="string", nullable=true)
     */
    private $tabelNumber;

    /**
     * @var string|null
     *
     * @ORM\Column(name="patronymic", type="string", nullable=true, unique=false)
     */
    private $patronymic;

    /**
     * @var string|null
     *
     * @ORM\Column(name="phone_number", type="string", nullable=true, unique=false)
     */
    private $phoneNumber;

    /**
     * @var string|null
     *
     * @ORM\Column(name="inn", type="string", nullable=true, options={"comment"="ИНН"})
     */
    private $inn;

    /**
     * @var string|null
     *
     * @ORM\Column(name="snils", type="string", nullable=true, options={"comment"="СНИЛС"})
     */
    private $snils;

    /**
     * @var string|null
     *
     * @ORM\Column(name="position", type="string", nullable=true)
     */
    private $position;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="brith_at", type="datetime", nullable=true, options={"comment"="Дата рождения"})
     */
    private $brithAt;

    /**
     * @var uuid|null
     *
     * @ORM\Column(name="autor_uuid", type="uuid", nullable=true)
     */
    private $autorId;

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
     * @var \Domain\Entities\Subscriber\Account
     *
     * @ORM\OneToOne(targetEntity="Domain\Entities\Subscriber\Account", inversedBy="workpeople")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="account_id", referencedColumnName="id", unique=true)
     * })
     */
    private $account;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Domain\Entities\Business\Master\Timesheet", mappedBy="workpeople")
     */
    private $timesheet;

    /**
     * @var \Domain\Entities\Business\Company\Company
     *
     * @ORM\ManyToOne(targetEntity="Domain\Entities\Business\Company\Company", inversedBy="workpeople")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="company_id", referencedColumnName="id")
     * })
     */
    private $company;

    /**
     * @var \Domain\Entities\Business\Directory\Profession
     *
     * @ORM\ManyToOne(targetEntity="Domain\Entities\Business\Directory\Profession", inversedBy="workpeoples")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="profession_id", referencedColumnName="id")
     * })
     */
    private $profession;

    /**
     * @var \Domain\Entities\Business\Master\Brigade
     *
     * @ORM\ManyToOne(targetEntity="Domain\Entities\Business\Master\Brigade", inversedBy="workpeoples")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="brigade_id", referencedColumnName="id")
     * })
     */
    private $brigade;

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
     * @var \Domain\Entities\Subscriber\Account
     *
     * @ORM\ManyToOne(targetEntity="Domain\Entities\Subscriber\Account")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="master", referencedColumnName="id")
     * })
     */
    private $master;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->timesheet = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set surname.
     *
     * @param string|null $surname
     *
     * @return Workpeople
     */
    public function setSurname($surname = null)
    {
        $this->surname = $surname;

        return $this;
    }

    /**
     * Get surname.
     *
     * @return string|null
     */
    public function getSurname()
    {
        return $this->surname;
    }

    /**
     * Set name.
     *
     * @param string|null $name
     *
     * @return Workpeople
     */
    public function setName($name = null)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name.
     *
     * @return string|null
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set tabelNumber.
     *
     * @param string|null $tabelNumber
     *
     * @return Workpeople
     */
    public function setTabelNumber($tabelNumber = null)
    {
        $this->tabelNumber = $tabelNumber;

        return $this;
    }

    /**
     * Get tabelNumber.
     *
     * @return string|null
     */
    public function getTabelNumber()
    {
        return $this->tabelNumber;
    }

    /**
     * Set patronymic.
     *
     * @param string|null $patronymic
     *
     * @return Workpeople
     */
    public function setPatronymic($patronymic = null)
    {
        $this->patronymic = $patronymic;

        return $this;
    }

    /**
     * Get patronymic.
     *
     * @return string|null
     */
    public function getPatronymic()
    {
        return $this->patronymic;
    }

    /**
     * Set phoneNumber.
     *
     * @param string|null $phoneNumber
     *
     * @return Workpeople
     */
    public function setPhoneNumber($phoneNumber = null)
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    /**
     * Get phoneNumber.
     *
     * @return string|null
     */
    public function getPhoneNumber()
    {
        return $this->phoneNumber;
    }

    /**
     * Set inn.
     *
     * @param string|null $inn
     *
     * @return Workpeople
     */
    public function setInn($inn = null)
    {
        $this->inn = $inn;

        return $this;
    }

    /**
     * Get inn.
     *
     * @return string|null
     */
    public function getInn()
    {
        return $this->inn;
    }

    /**
     * Set snils.
     *
     * @param string|null $snils
     *
     * @return Workpeople
     */
    public function setSnils($snils = null)
    {
        $this->snils = $snils;

        return $this;
    }

    /**
     * Get snils.
     *
     * @return string|null
     */
    public function getSnils()
    {
        return $this->snils;
    }

    /**
     * Set position.
     *
     * @param string|null $position
     *
     * @return Workpeople
     */
    public function setPosition($position = null)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Get position.
     *
     * @return string|null
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Set brithAt.
     *
     * @param \DateTime|null $brithAt
     *
     * @return Workpeople
     */
    public function setBrithAt($brithAt = null)
    {
        $this->brithAt = $brithAt;

        return $this;
    }

    /**
     * Get brithAt.
     *
     * @return \DateTime|null
     */
    public function getBrithAt()
    {
        return $this->brithAt;
    }

    /**
     * Set autorId.
     *
     * @param uuid|null $autorId
     *
     * @return Workpeople
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
     * Set createdAt.
     *
     * @param \DateTime $createdAt
     *
     * @return Workpeople
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
     * @return Workpeople
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
     * Set active.
     *
     * @param bool|null $active
     *
     * @return Workpeople
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
     * @return Workpeople
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
     * @return Workpeople
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
     * Set account.
     *
     * @param \Domain\Entities\Subscriber\Account|null $account
     *
     * @return Workpeople
     */
    public function setAccount(\Domain\Entities\Subscriber\Account $account = null)
    {
        $this->account = $account;

        return $this;
    }

    /**
     * Get account.
     *
     * @return \Domain\Entities\Subscriber\Account|null
     */
    public function getAccount()
    {
        return $this->account;
    }

    /**
     * Add timesheet.
     *
     * @param \Domain\Entities\Business\Master\Timesheet $timesheet
     *
     * @return Workpeople
     */
    public function addTimesheet(\Domain\Entities\Business\Master\Timesheet $timesheet)
    {
        $this->timesheet[] = $timesheet;

        return $this;
    }

    /**
     * Remove timesheet.
     *
     * @param \Domain\Entities\Business\Master\Timesheet $timesheet
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeTimesheet(\Domain\Entities\Business\Master\Timesheet $timesheet)
    {
        return $this->timesheet->removeElement($timesheet);
    }

    /**
     * Get timesheet.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTimesheet()
    {
        return $this->timesheet;
    }

    /**
     * Set company.
     *
     * @param \Domain\Entities\Business\Company\Company|null $company
     *
     * @return Workpeople
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
     * Set profession.
     *
     * @param \Domain\Entities\Business\Directory\Profession|null $profession
     *
     * @return Workpeople
     */
    public function setProfession(\Domain\Entities\Business\Directory\Profession $profession = null)
    {
        $this->profession = $profession;

        return $this;
    }

    /**
     * Get profession.
     *
     * @return \Domain\Entities\Business\Directory\Profession|null
     */
    public function getProfession()
    {
        return $this->profession;
    }

    /**
     * Set brigade.
     *
     * @param \Domain\Entities\Business\Master\Brigade|null $brigade
     *
     * @return Workpeople
     */
    public function setBrigade(\Domain\Entities\Business\Master\Brigade $brigade = null)
    {
        $this->brigade = $brigade;

        return $this;
    }

    /**
     * Get brigade.
     *
     * @return \Domain\Entities\Business\Master\Brigade|null
     */
    public function getBrigade()
    {
        return $this->brigade;
    }

    /**
     * Set autor.
     *
     * @param \Domain\Entities\Subscriber\Account|null $autor
     *
     * @return Workpeople
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

    /**
     * Set master.
     *
     * @param \Domain\Entities\Subscriber\Account|null $master
     *
     * @return Workpeople
     */
    public function setMaster(\Domain\Entities\Subscriber\Account $master = null)
    {
        $this->master = $master;

        return $this;
    }

    /**
     * Get master.
     *
     * @return \Domain\Entities\Subscriber\Account|null
     */
    public function getMaster()
    {
        return $this->master;
    }
}
