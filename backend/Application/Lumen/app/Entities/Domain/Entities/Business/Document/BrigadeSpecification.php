<?php

namespace Domain\Entities\Business\Document;

/**
 * BrigadeSpecification
 */
class BrigadeSpecification
{
    /**
     * @var uuid
     */
    private $id;

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
     * @var \DateTime|null
     */
    private $deletedAt;

    /**
     * @var bool|null
     */
    private $active;

    /**
     * @var bool|null
     */
    private $delete = false;

    /**
     * @var \Domain\Entities\Business\Company\Company
     */
    private $company;

    /**
     * @var \Domain\Entities\Business\Master\Brigade
     */
    private $brigade;

    /**
     * @var \Domain\Entities\Subscriber\Account
     */
    private $autor;

    /**
     * @var \Domain\Entities\Business\Objects\Specification
     */
    private $specification;


}
