<?php

namespace Domain\Entities\Business\Directory;

/**
 * TypeWorks
 */
class TypeWorks
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
    private $fullname;

    /**
     * @var float|null
     */
    private $quantity;

    /**
     * @var string|null
     */
    private $code;

    /**
     * @var string|null
     */
    private $units;

    /**
     * @var float|null
     */
    private $price;

    /**
     * @var int|null
     */
    private $sysid;

    /**
     * @var string|null
     */
    private $category;

    /**
     * @var string|null
     */
    private $spcode;

    /**
     * @var string|null
     */
    private $nrcode;

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
     * @var \DateTime|null
     */
    private $deletedAt;


}
