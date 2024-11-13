<?php

namespace Domain\Entities\Business\Objects;

/**
 * Contacts
 */
class Contacts
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $surname;

    /**
     * @var string|null
     */
    private $name;

    /**
     * @var string|null
     */
    private $patronymic;

    /**
     * @var string|null
     */
    private $fullname;

    /**
     * @var string|null
     */
    private $phone;

    /**
     * @var string|null
     */
    private $email;

    /**
     * @var string|null
     */
    private $employee;

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

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $objects = array();

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->objects = new \Doctrine\Common\Collections\ArrayCollection();
    }

}
