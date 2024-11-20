<?php

namespace Domain\Entities\Utils;

use Doctrine\ORM\Mapping as ORM;

/**
 * Currency
 *
 * @ORM\Table(name="currency")
 * @ORM\Entity
 */
class Currency
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
     * @ORM\Column(name="name", type="string", nullable=false, unique=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string", nullable=false, unique=true)
     */
    private $code;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", nullable=false)
     */
    private $title;

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
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Domain\Entities\Business\Directory\Price", mappedBy="currency")
     */
    private $price;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->price = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Currency
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
     * Set code.
     *
     * @param string $code
     *
     * @return Currency
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code.
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set title.
     *
     * @param string $title
     *
     * @return Currency
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set createdAt.
     *
     * @param \DateTime $createdAt
     *
     * @return Currency
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
     * @return Currency
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
     * @return Currency
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
     * @return Currency
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
     * @return Currency
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
     * Add price.
     *
     * @param \Domain\Entities\Business\Directory\Price $price
     *
     * @return Currency
     */
    public function addPrice(\Domain\Entities\Business\Directory\Price $price)
    {
        $this->price[] = $price;

        return $this;
    }

    /**
     * Remove price.
     *
     * @param \Domain\Entities\Business\Directory\Price $price
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removePrice(\Domain\Entities\Business\Directory\Price $price)
    {
        return $this->price->removeElement($price);
    }

    /**
     * Get price.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPrice()
    {
        return $this->price;
    }
}
