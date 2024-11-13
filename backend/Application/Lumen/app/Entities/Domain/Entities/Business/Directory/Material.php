<?php

namespace Domain\Entities\Business\Directory;

/**
 * Material
 */
class Material
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
     * @var string|null
     */
    private $description;

    /**
     * @var string|null
     */
    private $code;

    /**
     * @var string|null
     */
    private $vendor;

    /**
     * @var string|null
     */
    private $articul;

    /**
     * @var int|null
     */
    private $sysid;

    /**
     * @var uuid|null
     */
    private $autorId;

    /**
     * @var bool|null
     */
    private $isGroup = false;

    /**
     * @var bool|null
     */
    private $active;

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
     * @var \Doctrine\Common\Collections\Collection
     */
    private $specificationmaterial;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $children;

    /**
     * @var \Domain\Entities\Business\Company\Company
     */
    private $company;

    /**
     * @var \Domain\Entities\Business\Directory\Material
     */
    private $parent;

    /**
     * @var \Domain\Entities\Utils\Units
     */
    private $unit;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $prices = array();

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->specificationmaterial = new \Doctrine\Common\Collections\ArrayCollection();
        $this->children = new \Doctrine\Common\Collections\ArrayCollection();
        $this->prices = new \Doctrine\Common\Collections\ArrayCollection();
    }

}
