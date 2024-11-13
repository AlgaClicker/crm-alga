<?php

namespace Domain\Entities\Services;

/**
 * ChatMessages
 */
class ChatMessages
{
    /**
     * @var uuid
     */
    private $id;

    /**
     * @var string|null
     */
    private $accountTo;

    /**
     * @var string
     */
    private $message;

    /**
     * @var uuid|null
     */
    private $messageTo;

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
     * @var \Domain\Entities\Subscriber\Account
     */
    private $autor;

    /**
     * @var \Domain\Entities\Services\Chat
     */
    private $chat;

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
