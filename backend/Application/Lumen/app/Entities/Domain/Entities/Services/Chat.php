<?php

namespace Domain\Entities\Services;

/**
 * Chat
 */
class Chat
{
    /**
     * @var uuid
     */
    private $id;

    /**
     * @var uuid
     */
    private $room;

    /**
     * @var string
     */
    private $message;

    /**
     * @var bool|null
     */
    private $active = true;

    /**
     * @var bool|null
     */
    private $private = false;

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
     * @var \DateTime|null
     */
    private $readedAt;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $chat_messages;

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
    private $accountTo;

    /**
     * @var \Domain\Entities\Business\Master\Requisition
     */
    private $requisition;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $files = array();

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->chat_messages = new \Doctrine\Common\Collections\ArrayCollection();
        $this->files = new \Doctrine\Common\Collections\ArrayCollection();
    }

}
