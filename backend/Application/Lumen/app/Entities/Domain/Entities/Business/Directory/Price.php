<?php

namespace Domain\Entities\Business\Directory;

/**
 * Price
 */
class Price
{
    /**
     * @var uuid
     */
    private $id;

    /**
     * @var float
     */
    private $total;

    /**
     * @var uuid|null
     */
    private $autorId;

    /**
     * @var bool|null
     */
    private $active = true;

    /**
     * @var bool|null
     */
    private $delete = false;

    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var \DateTime|null
     */
    private $updatedAt;

    /**
     * @var \DateTime|null
     */
    private $deletedAt;

    /**
     * @var \Domain\Entities\Utils\Currency
     */
    private $currency;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $material = array();

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $specification = array();

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->material = new \Doctrine\Common\Collections\ArrayCollection();
        $this->specification = new \Doctrine\Common\Collections\ArrayCollection();
    }

}
