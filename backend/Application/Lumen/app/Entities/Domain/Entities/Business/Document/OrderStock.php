<?php

namespace Domain\Entities\Business\Document;

/**
 * OrderStock
 */
class OrderStock
{
    /**
     * @var uuid
     */
    private $id;

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
     * @var int|null
     */
    private $quantity;

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
     * @var \Domain\Entities\Business\Company\Stock
     */
    private $stock;


}
