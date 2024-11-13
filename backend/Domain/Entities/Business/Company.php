<?php

namespace Domain\Entities\Business;

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
     * @var int
     *
     * @ORM\Column(name="id", type="integer", options={"unsigned"=true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
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
     * @var int|null
     *
     * @ORM\Column(name="inn", type="bigint", nullable=true)
     */
    private $inn;

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
     * @var int|null
     *
     * @ORM\Column(name="autor_id", type="integer", nullable=true)
     */
    private $autorId;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="active", type="boolean", nullable=true)
     */
    private $active;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Domain\Entities\Business\Stock", mappedBy="company")
     */
    private $stock;

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
    private $account;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->stock = new \Doctrine\Common\Collections\ArrayCollection();
        $this->objects = new \Doctrine\Common\Collections\ArrayCollection();
        $this->account = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id.
     *
     * @return int
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
     * @param int|null $autorId
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
     * @return int|null
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
     * Add stock.
     *
     * @param \Domain\Entities\Business\Stock $stock
     *
     * @return Company
     */
    public function addStock(\Domain\Entities\Business\Stock $stock)
    {
        $this->stock[] = $stock;

        return $this;
    }

    /**
     * Remove stock.
     *
     * @param \Domain\Entities\Business\Stock $stock
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeStock(\Domain\Entities\Business\Stock $stock)
    {
        return $this->stock->removeElement($stock);
    }

    /**
     * Get stock.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getStock()
    {
        return $this->stock;
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
}
