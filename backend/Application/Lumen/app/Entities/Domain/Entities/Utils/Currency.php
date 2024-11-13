<?php

namespace Domain\Entities\Utils;

/**
 * Currency
 */
class Currency
{
    /**
     * @var uuid
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $code;

    /**
     * @var string
     */
    private $title;

    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var \DateTime|null
     */
    private $updatedAt;

    /**
     * @var uuid|null
     */
    private $autorId;

    /**
     * @var bool|null
     */
    private $active;

    /**
     * @var bool|null
     */
    private $delete = false;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $price;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->price = new \Doctrine\Common\Collections\ArrayCollection();
    }

}
