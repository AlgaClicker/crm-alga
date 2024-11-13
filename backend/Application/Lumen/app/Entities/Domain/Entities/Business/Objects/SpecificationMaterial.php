<?php

namespace Domain\Entities\Business\Objects;

/**
 * SpecificationMaterial
 */
class SpecificationMaterial
{
    /**
     * @var uuid
     */
    private $id;

    /**
     * @var int|null
     */
    private $index;

    /**
     * @var string|null
     */
    private $position;

    /**
     * @var string|null
     */
    private $rationale;

    /**
     * @var string|null
     */
    private $fullname;

    /**
     * @var string|null
     */
    private $description;

    /**
     * @var string|null
     */
    private $type;

    /**
     * @var string|null
     */
    private $code;

    /**
     * @var string|null
     */
    private $vendor;

    /**
     * @var float|null
     */
    private $quantity;

    /**
     * @var float|null
     */
    private $massUnit;

    /**
     * @var bool|null
     */
    private $isGroup;

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
    private $draft;

    /**
     * @var bool|null
     */
    private $delete = false;

    /**
     * @var \DateTime|null
     */
    private $deletedAt;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $specificationMaterialCalculation;

    /**
     * @var \Domain\Entities\Business\Objects\SpecificationMaterial
     */
    private $parent;

    /**
     * @var \Domain\Entities\Business\Directory\Material
     */
    private $material;

    /**
     * @var \Domain\Entities\Utils\Units
     */
    private $unit;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $specification = array();

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->specificationMaterialCalculation = new \Doctrine\Common\Collections\ArrayCollection();
        $this->specification = new \Doctrine\Common\Collections\ArrayCollection();
    }

}
