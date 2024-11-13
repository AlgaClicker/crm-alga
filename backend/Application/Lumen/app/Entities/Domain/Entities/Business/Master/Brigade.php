<?php

namespace Domain\Entities\Business\Master;

/**
 * Brigade
 */
class Brigade
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
     * @var string|null
     */
    private $fullname;

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
     * @var \Doctrine\Common\Collections\Collection
     */
    private $brigade_pay;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $workpeoples;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $brigadeSpecification;

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
    private $master;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->brigade_pay = new \Doctrine\Common\Collections\ArrayCollection();
        $this->workpeoples = new \Doctrine\Common\Collections\ArrayCollection();
        $this->brigadeSpecification = new \Doctrine\Common\Collections\ArrayCollection();
    }

}
