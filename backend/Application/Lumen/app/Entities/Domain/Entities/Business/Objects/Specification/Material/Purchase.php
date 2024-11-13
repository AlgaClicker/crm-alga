<?php

namespace Domain\Entities\Business\Objects\Specification\Material;

/**
 * Purchase
 */
class Purchase
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
     * @var int|null
     */
    private $tax;

    /**
     * @var float|null
     */
    private $taxAmount;

    /**
     * @var string
     */
    private $type;

    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var \DateTime|null
     */
    private $updatedAt;

    /**
     * @var bool|null
     */
    private $fixed;

    /**
     * @var bool|null
     */
    private $active;

    /**
     * @var bool|null
     */
    private $delete = false;

    /**
     * @var \DateTime|null
     */
    private $deletedAt;

    /**
     * @var \Domain\Entities\Business\Objects\SpecificationMaterial
     */
    private $specificationMaterial;

    /**
     * @var \Domain\Entities\Business\Objects\Specification
     */
    private $specification;

    /**
     * @var \Domain\Entities\Business\Company\Company
     */
    private $company;

    /**
     * @var \Domain\Entities\Business\Directory\Partner
     */
    private $partner;

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
