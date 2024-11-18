<?php

namespace Domain\Entities\Services;

/**
 * Files
 */
class Files
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
    private $type;

    /**
     * @var string|null
     */
    private $patch;

    /**
     * @var string|null
     */
    private $mime;

    /**
     * @var string|null
     */
    private $hash;

    /**
     * @var int|null
     */
    private $size;

    /**
     * @var string|null
     */
    private $storage;

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
     * @var string|null
     */
    private $public;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $objects = array();

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $specification = array();

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $notification = array();

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $workflow = array();

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $tasks = array();

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $chats = array();

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->objects = new \Doctrine\Common\Collections\ArrayCollection();
        $this->specification = new \Doctrine\Common\Collections\ArrayCollection();
        $this->notification = new \Doctrine\Common\Collections\ArrayCollection();
        $this->workflow = new \Doctrine\Common\Collections\ArrayCollection();
        $this->tasks = new \Doctrine\Common\Collections\ArrayCollection();
        $this->chats = new \Doctrine\Common\Collections\ArrayCollection();
    }

}
