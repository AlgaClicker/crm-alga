<?php

namespace Domain\Entities\Business\Master;

/**
 * RequisitionMaterials
 */
class RequisitionMaterials
{
    /**
     * @var uuid
     */
    private $id;

    /**
     * @var string|null
     */
    private $name;

    /**
     * @var float
     */
    private $quantity;

    /**
     * @var string|null
     */
    private $description;

    /**
     * @var string|null
     */
    private $status;

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
     * @var \Domain\Entities\Business\Master\Requisition
     */
    private $requisition;

    /**
     * @var \Domain\Entities\Business\Objects\SpecificationMaterial
     */
    private $specificationMaterial;

    /**
     * @var \Domain\Entities\Business\Directory\Material
     */
    private $directoryMaterial;

    /**
     * @var \Domain\Entities\Business\Directory\Material
     */
    private $material;

    /**
     * @var \Domain\Entities\Subscriber\Account
     */
    private $autor;

    /**
     * @var \Domain\Entities\Utils\Units
     */
    private $unit;


}
