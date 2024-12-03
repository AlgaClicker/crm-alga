<?php

namespace Domain\Entities\Business\Master;

/**
 * Requisition
 */
class Requisition
{
    /**
     * @var uuid
     */
    private $id;

    /**
     * @var string
     */
    private $number;

    /**
     * @var int
     */
    private $index;

    /**
     * @var string|null
     */
    private $status;

    /**
     * @var \DateTime
     */
    private $endAt;

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
    private $fixed = false;

    /**
     * @var string|null
     */
    private $description;

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
    private $materials;

    /**
     * @var \Domain\Entities\Utils\Units
     */
    private $unit;

    /**
     * @var \Domain\Entities\Business\Company\Company
     */
    private $company;

    /**
     * @var \Domain\Entities\Subscriber\Account
     */
    private $autor;

    /**
     * @var \Domain\Entities\Subscriber\Account
     */
    private $manager;

    /**
     * @var \Domain\Entities\Business\Objects\Specification
     */
    private $specification;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->materials = new \Doctrine\Common\Collections\ArrayCollection();
    }

}
