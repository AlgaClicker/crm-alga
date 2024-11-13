<?php

namespace Domain\Entities\Business\Directory;

/**
 * Profession
 */
class Profession
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
    private $active = true;

    /**
     * @var bool|null
     */
    private $fixed = false;

    /**
     * @var \DateTime|null
     */
    private $deletedAt;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $workpeoples;

    /**
     * @var \Domain\Entities\Business\Company\Company
     */
    private $company;

    /**
     * @var \Domain\Entities\Subscriber\Account
     */
    private $autor;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->workpeoples = new \Doctrine\Common\Collections\ArrayCollection();
    }

}
