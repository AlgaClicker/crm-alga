<?php

namespace Domain\Entities\Utils;

/**
 * Units
 */
class Units
{
    /**
     * @var uuid
     */
    private $id;

    /**
     * @var string
     */
    private $code;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $title;

    /**
     * @var float
     */
    private $factor;

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
    private $material;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $specificationmaterial;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->material = new \Doctrine\Common\Collections\ArrayCollection();
        $this->specificationmaterial = new \Doctrine\Common\Collections\ArrayCollection();
    }

}
