<?php

namespace Domain\Entities\Services;

/**
 * Notification
 */
class Notification
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
     * @var string
     */
    private $message;

    /**
     * @var string|null
     */
    private $url;

    /**
     * @var array
     */
    private $methods = 'local';

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
    private $delete = false;

    /**
     * @var bool|null
     */
    private $readed = false;

    /**
     * @var \DateTime|null
     */
    private $deletedAt;

    /**
     * @var \Domain\Entities\Subscriber\Account
     */
    private $toAccount;

    /**
     * @var \Domain\Entities\Subscriber\Account
     */
    private $fromAccount;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $files = array();

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->files = new \Doctrine\Common\Collections\ArrayCollection();
    }

}
