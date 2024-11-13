<?php

namespace Domain\Entities\Business\Directory;

use Doctrine\ORM\Mapping as ORM;

/**
 * Recipient
 *
 * @ORM\Table(name="recipient", indexes={@ORM\Index(name="name_recipient_index", columns={"name"})})
 * @ORM\Entity
 */
class Recipient
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
     * @var int|null
     *
     * @ORM\Column(name="inn", type="integer", nullable=true)
     */
    private $inn;

    /**
     * @var int|null
     *
     * @ORM\Column(name="kpp", type="integer", nullable=true)
     */
    private $kpp;

    /**
     * @var int|null
     *
     * @ORM\Column(name="ogrn", type="integer", nullable=true)
     */
    private $ogrn;

    /**
     * @var uuid|null
     *
     * @ORM\Column(name="autor_uuid", type="uuid", nullable=true)
     */
    private $autorId;

    /**
     * @var int|null
     *
     * @ORM\Column(name="parent", type="integer", nullable=true)
     */
    private $parent = 0;

    /**
     * @var int|null
     *
     * @ORM\Column(name="level", type="integer", nullable=true)
     */
    private $level = 0;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="isGroup", type="boolean", nullable=true)
     */
    private $isGroup = false;

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
     * @var \DateTime|null
     *
     * @ORM\Column(name="deleted_at", type="datetime", nullable=true)
     */
    private $deletedAt;

    /**
     * @var \Domain\Entities\Business\Company\Company
     *
     * @ORM\ManyToOne(targetEntity="Domain\Entities\Business\Company\Company", inversedBy="recipient")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="company_id", referencedColumnName="id")
     * })
     */
    private $company;

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
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Domain\Entities\Business\Directory\Bank", mappedBy="recipient")
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
     * Set name.
     *
     * @param string $name
     *
     * @return Recipient
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
     * @return Recipient
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
     * Set inn.
     *
     * @param int|null $inn
     *
     * @return Recipient
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
     * @return Recipient
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
     * Set ogrn.
     *
     * @param int|null $ogrn
     *
     * @return Recipient
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
     * Set autorId.
     *
     * @param uuid|null $autorId
     *
     * @return Recipient
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
     * Set parent.
     *
     * @param int|null $parent
     *
     * @return Recipient
     */
    public function setParent($parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent.
     *
     * @return int|null
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Set level.
     *
     * @param int|null $level
     *
     * @return Recipient
     */
    public function setLevel($level = null)
    {
        $this->level = $level;

        return $this;
    }

    /**
     * Get level.
     *
     * @return int|null
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * Set isGroup.
     *
     * @param bool|null $isGroup
     *
     * @return Recipient
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
     * Set active.
     *
     * @param bool|null $active
     *
     * @return Recipient
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
     * @return Recipient
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
     * @return Recipient
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
     * @return Recipient
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
     * @return Recipient
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
     * Set company.
     *
     * @param \Domain\Entities\Business\Company\Company|null $company
     *
     * @return Recipient
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
     * Set autor.
     *
     * @param \Domain\Entities\Subscriber\Account|null $autor
     *
     * @return Recipient
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
     * Add bank.
     *
     * @param \Domain\Entities\Business\Directory\Bank $bank
     *
     * @return Recipient
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
