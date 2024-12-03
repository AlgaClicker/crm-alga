<?php

namespace Domain\Entities\Services;

use Doctrine\ORM\Mapping as ORM;

/**
 * Chat
 *
 * @ORM\Table(name="chat")
 * @ORM\Entity
 */
class Chat
{
    /**
     * @var uuid
     *
     * @ORM\Column(name="id", type="uuid", options={"unsigned"=true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="CUSTOM")
     */
    private $id;

    /**
     * @var uuid
     *
     * @ORM\Column(name="room", type="uuid")
     */
    private $room;

    /**
     * @var string
     *
     * @ORM\Column(name="message", type="text", nullable=false)
     */
    private $message;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="active", type="boolean", nullable=true, options={"default"="1"})
     */
    private $active = true;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="private", type="boolean", nullable=true)
     */
    private $private = false;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="delete", type="boolean", nullable=true)
     */
    private $delete = false;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="readed", type="boolean", nullable=true)
     */
    private $readed = false;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    private $createdAt;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="deleted_at", type="datetime", nullable=true)
     */
    private $deletedAt;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="readed_at", type="datetime", nullable=true)
     */
    private $readedAt;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Domain\Entities\Services\ChatMessages", mappedBy="chat")
     */
    private $chat_messages;

    /**
     * @var \Domain\Entities\Business\Company\Company
     *
     * @ORM\ManyToOne(targetEntity="Domain\Entities\Business\Company\Company")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="company_id", referencedColumnName="id")
     * })
     */
    private $company;

    /**
     * @var \Domain\Entities\Subscriber\Account
     *
     * @ORM\ManyToOne(targetEntity="Domain\Entities\Subscriber\Account")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="autor", referencedColumnName="id")
     * })
     */
    private $autor;

    /**
     * @var \Domain\Entities\Subscriber\Account
     *
     * @ORM\ManyToOne(targetEntity="Domain\Entities\Subscriber\Account")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="account_to", referencedColumnName="id")
     * })
     */
    private $accountTo;

    /**
     * @var \Domain\Entities\Business\Master\Requisition
     *
     * @ORM\ManyToOne(targetEntity="Domain\Entities\Business\Master\Requisition")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="requisition_id", referencedColumnName="id")
     * })
     */
    private $requisition;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Domain\Entities\Services\Files", mappedBy="chats")
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

    /**
     * Get id.
     *
     * @return uuid
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set room.
     *
     * @param uuid $room
     *
     * @return Chat
     */
    public function setRoom($room)
    {
        $this->room = $room;

        return $this;
    }

    /**
     * Get room.
     *
     * @return uuid
     */
    public function getRoom()
    {
        return $this->room;
    }

    /**
     * Set message.
     *
     * @param string $message
     *
     * @return Chat
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get message.
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set active.
     *
     * @param bool|null $active
     *
     * @return Chat
     */
    public function setActive($active = null)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get active.
     *
     * @return bool|null
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * Set private.
     *
     * @param bool|null $private
     *
     * @return Chat
     */
    public function setPrivate($private = null)
    {
        $this->private = $private;

        return $this;
    }

    /**
     * Get private.
     *
     * @return bool|null
     */
    public function getPrivate()
    {
        return $this->private;
    }

    /**
     * Set delete.
     *
     * @param bool|null $delete
     *
     * @return Chat
     */
    public function setDelete($delete = null)
    {
        $this->delete = $delete;

        return $this;
    }

    /**
     * Get delete.
     *
     * @return bool|null
     */
    public function getDelete()
    {
        return $this->delete;
    }

    /**
     * Set readed.
     *
     * @param bool|null $readed
     *
     * @return Chat
     */
    public function setReaded($readed = null)
    {
        $this->readed = $readed;

        return $this;
    }

    /**
     * Get readed.
     *
     * @return bool|null
     */
    public function getReaded()
    {
        return $this->readed;
    }

    /**
     * Set createdAt.
     *
     * @param \DateTime $createdAt
     *
     * @return Chat
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt.
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt.
     *
     * @param \DateTime|null $updatedAt
     *
     * @return Chat
     */
    public function setUpdatedAt($updatedAt = null)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt.
     *
     * @return \DateTime|null
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set deletedAt.
     *
     * @param \DateTime|null $deletedAt
     *
     * @return Chat
     */
    public function setDeletedAt($deletedAt = null)
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }

    /**
     * Get deletedAt.
     *
     * @return \DateTime|null
     */
    public function getDeletedAt()
    {
        return $this->deletedAt;
    }

    /**
     * Set readedAt.
     *
     * @param \DateTime|null $readedAt
     *
     * @return Chat
     */
    public function setReadedAt($readedAt = null)
    {
        $this->readedAt = $readedAt;

        return $this;
    }

    /**
     * Get readedAt.
     *
     * @return \DateTime|null
     */
    public function getReadedAt()
    {
        return $this->readedAt;
    }

    /**
     * Add chatMessage.
     *
     * @param \Domain\Entities\Services\ChatMessages $chatMessage
     *
     * @return Chat
     */
    public function addChatMessage(\Domain\Entities\Services\ChatMessages $chatMessage)
    {
        $this->chat_messages[] = $chatMessage;

        return $this;
    }

    /**
     * Remove chatMessage.
     *
     * @param \Domain\Entities\Services\ChatMessages $chatMessage
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeChatMessage(\Domain\Entities\Services\ChatMessages $chatMessage)
    {
        return $this->chat_messages->removeElement($chatMessage);
    }

    /**
     * Get chatMessages.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getChatMessages()
    {
        return $this->chat_messages;
    }

    /**
     * Set company.
     *
     * @param \Domain\Entities\Business\Company\Company|null $company
     *
     * @return Chat
     */
    public function setCompany(\Domain\Entities\Business\Company\Company $company = null)
    {
        $this->company = $company;

        return $this;
    }

    /**
     * Get company.
     *
     * @return \Domain\Entities\Business\Company\Company|null
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * Set autor.
     *
     * @param \Domain\Entities\Subscriber\Account|null $autor
     *
     * @return Chat
     */
    public function setAutor(\Domain\Entities\Subscriber\Account $autor = null)
    {
        $this->autor = $autor;

        return $this;
    }

    /**
     * Get autor.
     *
     * @return \Domain\Entities\Subscriber\Account|null
     */
    public function getAutor()
    {
        return $this->autor;
    }

    /**
     * Set accountTo.
     *
     * @param \Domain\Entities\Subscriber\Account|null $accountTo
     *
     * @return Chat
     */
    public function setAccountTo(\Domain\Entities\Subscriber\Account $accountTo = null)
    {
        $this->accountTo = $accountTo;

        return $this;
    }

    /**
     * Get accountTo.
     *
     * @return \Domain\Entities\Subscriber\Account|null
     */
    public function getAccountTo()
    {
        return $this->accountTo;
    }

    /**
     * Set requisition.
     *
     * @param \Domain\Entities\Business\Master\Requisition|null $requisition
     *
     * @return Chat
     */
    public function setRequisition(\Domain\Entities\Business\Master\Requisition $requisition = null)
    {
        $this->requisition = $requisition;

        return $this;
    }

    /**
     * Get requisition.
     *
     * @return \Domain\Entities\Business\Master\Requisition|null
     */
    public function getRequisition()
    {
        return $this->requisition;
    }

    /**
     * Add file.
     *
     * @param \Domain\Entities\Services\Files $file
     *
     * @return Chat
     */
    public function addFile(\Domain\Entities\Services\Files $file)
    {
        $this->files[] = $file;

        return $this;
    }

    /**
     * Remove file.
     *
     * @param \Domain\Entities\Services\Files $file
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeFile(\Domain\Entities\Services\Files $file)
    {
        return $this->files->removeElement($file);
    }

    /**
     * Get files.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFiles()
    {
        return $this->files;
    }
}
