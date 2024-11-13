<?php

namespace Domain\Entities\Business;

use Doctrine\ORM\Mapping as ORM;

/**
 * OrderStock
 *
 * @ORM\Table(name="order_stock")
 * @ORM\Entity
 */
class OrderStock
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
     * @var int|null
     *
     * @ORM\Column(name="quantity", type="integer", nullable=true)
     */
    private $quantity;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="active", type="boolean", nullable=true)
     */
    private $active;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="draft", type="boolean", nullable=true)
     */
    private $draft;

    /**
     * @var \Domain\Entities\Business\Stock
     *
     * @ORM\ManyToOne(targetEntity="Domain\Entities\Business\Stock")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="stock_id", referencedColumnName="id")
     * })
     */
    private $stock;


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
     * Set createdAt.
     *
     * @param \DateTime $createdAt
     *
     * @return OrderStock
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
     * @return OrderStock
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
     * @return OrderStock
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
     * Set quantity.
     *
     * @param int|null $quantity
     *
     * @return OrderStock
     */
    public function setQuantity($quantity = null)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * Get quantity.
     *
     * @return int|null
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Set active.
     *
     * @param bool|null $active
     *
     * @return OrderStock
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
     * Set draft.
     *
     * @param bool|null $draft
     *
     * @return OrderStock
     */
    public function setDraft($draft = null)
    {
        $this->draft = $draft;

        return $this;
    }

    /**
     * Get draft.
     *
     * @return bool|null
     */
    public function getDraft()
    {
        return $this->draft;
    }

    /**
     * Set stock.
     *
     * @param \Domain\Entities\Business\Stock|null $stock
     *
     * @return OrderStock
     */
    public function setStock(\Domain\Entities\Business\Stock $stock = null)
    {
        $this->stock = $stock;

        return $this;
    }

    /**
     * Get stock.
     *
     * @return \Domain\Entities\Business\Stock|null
     */
    public function getStock()
    {
        return $this->stock;
    }
}
