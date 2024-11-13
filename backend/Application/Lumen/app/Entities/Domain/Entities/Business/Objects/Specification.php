<?php

namespace Domain\Entities\Business\Objects;

/**
 * Specification
 */
class Specification
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
    private $locnum;

    /**
     * @var string|null
     */
    private $number;

    /**
     * @var string|null
     */
    private $constr;

    /**
     * @var string
     */
    private $description;

    /**
     * @var string|null
     */
    private $type;

    /**
     * @var int|null
     */
    private $idx;

    /**
     * @var uuid
     */
    private $autorId;

    /**
     * @var bool|null
     */
    private $active;

    /**
     * @var bool|null
     */
    private $draft = true;

    /**
     * @var bool|null
     */
    private $fixed = false;

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
     * @var \DateTime|null
     */
    private $archiveAt;

    /**
     * @var \Domain\Entities\Business\Company\Stock
     */
    private $stock;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $children;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $specificationTypeworks;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $specificationResources;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $requisitions;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $brigadeSpecification;

    /**
     * @var \Domain\Entities\Business\Objects\Objects
     */
    private $object;

    /**
     * @var \Domain\Entities\Business\Objects\Specification
     */
    private $parent;

    /**
     * @var \Domain\Entities\Subscriber\Account
     */
    private $autor;

    /**
     * @var \Domain\Entities\Subscriber\Account
     */
    private $manager;

    /**
     * @var \Domain\Entities\Business\Directory\Partner
     */
    private $partner;

    /**
     * @var \Domain\Entities\Business\Company\Company
     */
    private $company;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $specificationMaterials = array();

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $price = array();

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $files = array();

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $responsibles = array();

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->children = new \Doctrine\Common\Collections\ArrayCollection();
        $this->specificationTypeworks = new \Doctrine\Common\Collections\ArrayCollection();
        $this->specificationResources = new \Doctrine\Common\Collections\ArrayCollection();
        $this->requisitions = new \Doctrine\Common\Collections\ArrayCollection();
        $this->brigadeSpecification = new \Doctrine\Common\Collections\ArrayCollection();
        $this->specificationMaterials = new \Doctrine\Common\Collections\ArrayCollection();
        $this->price = new \Doctrine\Common\Collections\ArrayCollection();
        $this->files = new \Doctrine\Common\Collections\ArrayCollection();
        $this->responsibles = new \Doctrine\Common\Collections\ArrayCollection();
    }

}
