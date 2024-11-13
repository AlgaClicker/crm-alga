<?php

namespace Domain\Entities\Subscriber;

use Doctrine\ORM\Mapping as ORM;

/**
 * Roles
 *
 * @ORM\Table(name="roles")
 * @ORM\Entity
 */
class Roles
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
     * @ORM\Column(name="name", type="string", nullable=false, unique=false)
     */
    private $name;

    /**
     * @var string|null
     *
     * @ORM\Column(name="service", type="string", nullable=true, unique=false)
     */
    private $service;

    /**
     * @var array|null
     *
     * @ORM\Column(name="access", type="array", nullable=true)
     */
    private $access;

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
     * @ORM\Column(name="fixed", type="boolean", nullable=true)
     */
    private $fixed = false;

    /**
     * @var array|null
     *
     * @ORM\Column(name="autor_id", type="array", nullable=true)
     */
    private $autorId;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="active", type="boolean", nullable=true)
     */
    private $active;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="deleted_at", type="datetime", nullable=true)
     */
    private $deletedAt;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Domain\Entities\Subscriber\Account", mappedBy="roles", cascade={"persist","merge"})
     */
    private $account;

    /**
     * @var \Domain\Entities\Business\Company\Company
     *
     * @ORM\ManyToOne(targetEntity="Domain\Entities\Business\Company\Company", inversedBy="roles")
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
        $this->account = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Roles
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
     * Set service.
     *
     * @param string|null $service
     *
     * @return Roles
     */
    public function setService($service = null)
    {
        $this->service = $service;

        return $this;
    }

    /**
     * Get service.
     *
     * @return string|null
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     * Set access.
     *
     * @param array|null $access
     *
     * @return Roles
     */
    public function setAccess($access = null)
    {
        $this->access = $access;

        return $this;
    }

    /**
     * Get access.
     *
     * @return array|null
     */
    public function getAccess()
    {
        return $this->access;
    }

    /**
     * Set createdAt.
     *
     * @param \DateTime $createdAt
     *
     * @return Roles
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
     * @return Roles
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
     * Set fixed.
     *
     * @param bool|null $fixed
     *
     * @return Roles
     */
    public function setFixed($fixed = null)
    {
        $this->fixed = $fixed;

        return $this;
    }

    /**
     * Get fixed.
     *
     * @return bool|null
     */
    public function getFixed()
    {
        return $this->fixed;
    }

    /**
     * Set autorId.
     *
     * @param array|null $autorId
     *
     * @return Roles
     */
    public function setAutorId($autorId = null)
    {
        $this->autorId = $autorId;

        return $this;
    }

    /**
     * Get autorId.
     *
     * @return array|null
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
     * @return Roles
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
     * Set deletedAt.
     *
     * @param \DateTime|null $deletedAt
     *
     * @return Roles
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
     * Add account.
     *
     * @param \Domain\Entities\Subscriber\Account $account
     *
     * @return Roles
     */
    public function addAccount(\Domain\Entities\Subscriber\Account $account)
    {
        $this->account[] = $account;

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
        return $this->account->removeElement($account);
    }

    /**
     * Get account.
     *
     * @return \Doctrine\Common\Collections\Collection
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
     * @return Roles
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
