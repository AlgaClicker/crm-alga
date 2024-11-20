<?php

namespace Domain\Entities\Services;

use Doctrine\ORM\Mapping as ORM;

/**
 * Notification
 *
 * @ORM\Table(name="notification")
 * @ORM\Entity
 */
class Notification
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
     * @var string
     *
     * @ORM\Column(name="title", type="string", nullable=false)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="message", type="string", nullable=false)
     */
    private $message;

    /**
     * @var string|null
     *
     * @ORM\Column(name="url", type="string", nullable=true)
     */
    private $url;

    /**
     * @var array
     *
     * @ORM\Column(name="methods", type="array", nullable=false, options={"default"="local"})
     */
    private $methods = 'local';

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
     * @var bool|null
     *
     * @ORM\Column(name="active", type="boolean", nullable=true, options={"default"="1"})
     */
    private $active = true;

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
     * @var \DateTime|null
     *
     * @ORM\Column(name="deleted_at", type="datetime", nullable=true)
     */
    private $deletedAt;

    /**
     * @var \Domain\Entities\Subscriber\Account
     *
     * @ORM\ManyToOne(targetEntity="Domain\Entities\Subscriber\Account")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="to_account_id", referencedColumnName="id")
     * })
     */
    private $toAccount;

    /**
     * @var \Domain\Entities\Subscriber\Account
     *
     * @ORM\ManyToOne(targetEntity="Domain\Entities\Subscriber\Account")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="from_account", referencedColumnName="id")
     * })
     */
    private $fromAccount;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Domain\Entities\Services\Files", mappedBy="notification")
     */
    private $files = array();

    /**
     * Constructor
     */
    public function __construct()
    {
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
     * Set title.
     *
     * @param string $title
     *
     * @return Notification
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set message.
     *
     * @param string $message
     *
     * @return Notification
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
     * Set url.
     *
     * @param string|null $url
     *
     * @return Notification
     */
    public function setUrl($url = null)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url.
     *
     * @return string|null
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set methods.
     *
     * @param array $methods
     *
     * @return Notification
     */
    public function setMethods($methods)
    {
        $this->methods = $methods;

        return $this;
    }

    /**
     * Get methods.
     *
     * @return array
     */
    public function getMethods()
    {
        return $this->methods;
    }

    /**
     * Set createdAt.
     *
     * @param \DateTime $createdAt
     *
     * @return Notification
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
     * @return Notification
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
     * Set active.
     *
     * @param bool|null $active
     *
     * @return Notification
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
     * Set delete.
     *
     * @param bool|null $delete
     *
     * @return Notification
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
     * @return Notification
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
     * Set deletedAt.
     *
     * @param \DateTime|null $deletedAt
     *
     * @return Notification
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
     * Set toAccount.
     *
     * @param \Domain\Entities\Subscriber\Account|null $toAccount
     *
     * @return Notification
     */
    public function setToAccount(\Domain\Entities\Subscriber\Account $toAccount = null)
    {
        $this->toAccount = $toAccount;

        return $this;
    }

    /**
     * Get toAccount.
     *
     * @return \Domain\Entities\Subscriber\Account|null
     */
    public function getToAccount()
    {
        return $this->toAccount;
    }

    /**
     * Set fromAccount.
     *
     * @param \Domain\Entities\Subscriber\Account|null $fromAccount
     *
     * @return Notification
     */
    public function setFromAccount(\Domain\Entities\Subscriber\Account $fromAccount = null)
    {
        $this->fromAccount = $fromAccount;

        return $this;
    }

    /**
     * Get fromAccount.
     *
     * @return \Domain\Entities\Subscriber\Account|null
     */
    public function getFromAccount()
    {
        return $this->fromAccount;
    }

    /**
     * Add file.
     *
     * @param \Domain\Entities\Services\Files $file
     *
     * @return Notification
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
