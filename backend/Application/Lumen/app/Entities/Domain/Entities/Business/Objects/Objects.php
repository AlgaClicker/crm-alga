<?php

namespace Domain\Entities\Business\Objects;

/**
 * Objects
 */
class Objects
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
    private $address;

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
     * @var \DateTime|null
     */
    private $archiveAt;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $specification;

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
    private $contacts = array();

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $files = array();

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $accounts = array();

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $responsibles = array();

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->specification = new \Doctrine\Common\Collections\ArrayCollection();
        $this->contacts = new \Doctrine\Common\Collections\ArrayCollection();
        $this->files = new \Doctrine\Common\Collections\ArrayCollection();
        $this->accounts = new \Doctrine\Common\Collections\ArrayCollection();
        $this->responsibles = new \Doctrine\Common\Collections\ArrayCollection();
    }

}
