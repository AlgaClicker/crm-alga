<?php

namespace Domain\Entities\Business\Objects\Specification\Material;

/**
 * Calculation
 */
class Calculation
{
    /**
     * @var uuid
     */
    private $id;

    /**
     * @var float
     */
    private $amount;

    /**
     * @var bool|null
     */
    private $fixed = false;

    /**
     * @var bool|null
     */
    private $active = true;

    /**
     * @var string|null
     */
    private $description;

    /**
     * @var bool|null
     */
    private $draft = true;

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
     * @var \Domain\Entities\Business\Objects\Specification
     */
    private $specification;

    /**
     * @var \Domain\Entities\Business\Objects\SpecificationMaterial
     */
    private $specificationMaterial;

    /**
     * @var \Domain\Entities\Business\Company\Company
     */
    private $company;

    /**
     * @var \Domain\Entities\Subscriber\Account
     */
    private $autor;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $files = array();

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->files = new \Doctrine\Common\Collections\ArrayCollection();
    }

}
