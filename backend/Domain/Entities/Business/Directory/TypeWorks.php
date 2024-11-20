<?php

namespace Domain\Entities\Business\Directory;

use Doctrine\ORM\Mapping as ORM;

/**
 * TypeWorks
 *
 * @ORM\Table(name="typeworks")
 * @ORM\Entity
 */
class TypeWorks
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
     * @var float|null
     *
     * @ORM\Column(name="quantity", type="float", nullable=true)
     */
    private $quantity;

    /**
     * @var string|null
     *
     * @ORM\Column(name="code", type="string", nullable=true)
     */
    private $code;

    /**
     * @var string|null
     *
     * @ORM\Column(name="units", type="string", nullable=true)
     */
    private $units;

    /**
     * @var float|null
     *
     * @ORM\Column(name="price", type="float", nullable=true)
     */
    private $price;

    /**
     * @var int|null
     *
     * @ORM\Column(name="sysid", type="integer", nullable=true)
     */
    private $sysid;

    /**
     * @var string|null
     *
     * @ORM\Column(name="category", type="string", nullable=true)
     */
    private $category;

    /**
     * @var string|null
     *
     * @ORM\Column(name="spcode", type="string", nullable=true)
     */
    private $spcode;

    /**
     * @var string|null
     *
     * @ORM\Column(name="nrcode", type="string", nullable=true)
     */
    private $nrcode;

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
     * @var \DateTime|null
     *
     * @ORM\Column(name="deleted_at", type="datetime", nullable=true)
     */
    private $deletedAt;


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
     * @return TypeWorks
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
     * @return TypeWorks
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
     * Set quantity.
     *
     * @param float|null $quantity
     *
     * @return TypeWorks
     */
    public function setQuantity($quantity = null)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * Get quantity.
     *
     * @return float|null
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Set code.
     *
     * @param string|null $code
     *
     * @return TypeWorks
     */
    public function setCode($code = null)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code.
     *
     * @return string|null
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set units.
     *
     * @param string|null $units
     *
     * @return TypeWorks
     */
    public function setUnits($units = null)
    {
        $this->units = $units;

        return $this;
    }

    /**
     * Get units.
     *
     * @return string|null
     */
    public function getUnits()
    {
        return $this->units;
    }

    /**
     * Set price.
     *
     * @param float|null $price
     *
     * @return TypeWorks
     */
    public function setPrice($price = null)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price.
     *
     * @return float|null
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set sysid.
     *
     * @param int|null $sysid
     *
     * @return TypeWorks
     */
    public function setSysid($sysid = null)
    {
        $this->sysid = $sysid;

        return $this;
    }

    /**
     * Get sysid.
     *
     * @return int|null
     */
    public function getSysid()
    {
        return $this->sysid;
    }

    /**
     * Set category.
     *
     * @param string|null $category
     *
     * @return TypeWorks
     */
    public function setCategory($category = null)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category.
     *
     * @return string|null
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set spcode.
     *
     * @param string|null $spcode
     *
     * @return TypeWorks
     */
    public function setSpcode($spcode = null)
    {
        $this->spcode = $spcode;

        return $this;
    }

    /**
     * Get spcode.
     *
     * @return string|null
     */
    public function getSpcode()
    {
        return $this->spcode;
    }

    /**
     * Set nrcode.
     *
     * @param string|null $nrcode
     *
     * @return TypeWorks
     */
    public function setNrcode($nrcode = null)
    {
        $this->nrcode = $nrcode;

        return $this;
    }

    /**
     * Get nrcode.
     *
     * @return string|null
     */
    public function getNrcode()
    {
        return $this->nrcode;
    }

    /**
     * Set createdAt.
     *
     * @param \DateTime $createdAt
     *
     * @return TypeWorks
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
     * @return TypeWorks
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
     * @return TypeWorks
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
     * @return TypeWorks
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
     * @return TypeWorks
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
     * @return TypeWorks
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
}
