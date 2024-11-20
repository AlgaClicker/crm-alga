<?php

namespace Domain\Entities\Business\Master;

use Doctrine\ORM\Mapping as ORM;

/**
 * Brigade
 *
 * @ORM\Table(name="brigade")
 * @ORM\Entity
 */
class Brigade
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
     * @ORM\Column(name="name", type="string", nullable=true)
     */
    private $name;

    /**
     * @var string|null
     *
     * @ORM\Column(name="fullname", type="string", nullable=true)
     */
    private $fullname;

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
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Domain\Entities\Business\Payments\BrigadePay", mappedBy="brigade")
     */
    private $brigade_pay;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Domain\Entities\Business\Company\Workpeople", mappedBy="brigade")
     * @ORM\OrderBy({
     *     "surname"="ASC",
     *     "name"="ASC",
     *     "createdAt"="DESC"
     * })
     */
    private $workpeoples;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Domain\Entities\Business\Document\BrigadeSpecification", mappedBy="brigade")
     */
    private $brigadeSpecification;

    /**
     * @var \Domain\Entities\Business\Company\Company
     *
     * @ORM\ManyToOne(targetEntity="Domain\Entities\Business\Company\Company", inversedBy="brigades")
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
     *   @ORM\JoinColumn(name="autor_id", referencedColumnName="id")
     * })
     */
    private $autor;

    /**
     * @var \Domain\Entities\Subscriber\Account
     *
     * @ORM\ManyToOne(targetEntity="Domain\Entities\Subscriber\Account", inversedBy="brigades", cascade={"persist","merge"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="master_id", referencedColumnName="id")
     * })
     */
    private $master;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->brigade_pay = new \Doctrine\Common\Collections\ArrayCollection();
        $this->workpeoples = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @param string|null $name
     *
     * @return Brigade
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
     * Set fullname.
     *
     * @param string|null $fullname
     *
     * @return Brigade
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
     * Set createdAt.
     *
     * @param \DateTime $createdAt
     *
     * @return Brigade
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
     * @return Brigade
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
     * @return Brigade
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
     * Set active.
     *
     * @param bool|null $active
     *
     * @return Brigade
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
     * @return Brigade
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
     * Add brigadePay.
     *
     * @param \Domain\Entities\Business\Payments\BrigadePay $brigadePay
     *
     * @return Brigade
     */
    public function addBrigadePay(\Domain\Entities\Business\Payments\BrigadePay $brigadePay)
    {
        $this->brigade_pay[] = $brigadePay;

        return $this;
    }

    /**
     * Remove brigadePay.
     *
     * @param \Domain\Entities\Business\Payments\BrigadePay $brigadePay
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeBrigadePay(\Domain\Entities\Business\Payments\BrigadePay $brigadePay)
    {
        return $this->brigade_pay->removeElement($brigadePay);
    }

    /**
     * Get brigadePay.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getBrigadePay()
    {
        return $this->brigade_pay;
    }

    /**
     * Add workpeople.
     *
     * @param \Domain\Entities\Business\Company\Workpeople $workpeople
     *
     * @return Brigade
     */
    public function addWorkpeople(\Domain\Entities\Business\Company\Workpeople $workpeople)
    {
        $this->workpeoples[] = $workpeople;

        return $this;
    }

    /**
     * Remove workpeople.
     *
     * @param \Domain\Entities\Business\Company\Workpeople $workpeople
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeWorkpeople(\Domain\Entities\Business\Company\Workpeople $workpeople)
    {
        return $this->workpeoples->removeElement($workpeople);
    }

    /**
     * Get workpeoples.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getWorkpeoples()
    {
        return $this->workpeoples;
    }

    /**
     * Add brigadeSpecification.
     *
     * @param \Domain\Entities\Business\Document\BrigadeSpecification $brigadeSpecification
     *
     * @return Brigade
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

    /**
     * Set company.
     *
     * @param \Domain\Entities\Business\Company\Company|null $company
     *
     * @return Brigade
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
     * @return Brigade
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
     * @return Brigade
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
