<?php

namespace Domain\Entities\Business\Document;

/**
 * Contracts
 */
class Contracts
{
    /**
     * @var uuid
     */
    private $id;

    /**
     * @var string
     */
    private $title;

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
    private $active = true;

    /**
     * @var bool|null
     */
    private $delete = false;

    /**
     * @var \DateTime|null
     */
    private $deletedAt;

    /**
     * @var \Domain\Entities\Business\Company\Company
     */
    private $company;

    /**
     * @var \Domain\Entities\Business\Directory\Partner
     */
    private $partner;

    /**
     * @var \Domain\Entities\Subscriber\Account
     */
    private $autor;


}
