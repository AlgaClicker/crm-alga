<?php

namespace Domain\Entities\Business\Directory;

/**
 * Recipient
 */
class Recipient
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
     * @var int|null
     */
    private $inn;

    /**
     * @var int|null
     */
    private $kpp;

    /**
     * @var int|null
     */
    private $ogrn;

    /**
     * @var uuid|null
     */
    private $autorId;

    /**
     * @var int|null
     */
    private $parent = 0;

    /**
     * @var int|null
     */
    private $level = 0;

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
     * @var \Domain\Entities\Business\Company\Company
     */
    private $company;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $bank = array();

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->bank = new \Doctrine\Common\Collections\ArrayCollection();
    }

}
