<?php

namespace Domain\Entities\Services;

use Doctrine\ORM\Mapping as ORM;

/**
 * Files
 *
 * @ORM\Table(name="files")
 * @ORM\Entity
 */
class Files
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
     * @var string|null
     *
     * @ORM\Column(name="name", type="string", nullable=true)
     */
    private $name;

    /**
     * @var string|null
     *
     * @ORM\Column(name="type", type="string", nullable=true)
     */
    private $type;

    /**
     * @var string|null
     *
     * @ORM\Column(name="patch", type="string", nullable=true)
     */
    private $patch;

    /**
     * @var string|null
     *
     * @ORM\Column(name="mime", type="string", nullable=true)
     */
    private $mime;

    /**
     * @var string|null
     *
     * @ORM\Column(name="hash", type="string", nullable=true, unique=true)
     */
    private $hash;

    /**
     * @var int|null
     *
     * @ORM\Column(name="size", type="integer", nullable=true, unique=false)
     */
    private $size;

    /**
     * @var string|null
     *
     * @ORM\Column(name="storage", type="string", nullable=true)
     */
    private $storage;

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
     * @var uuid|null
     *
     * @ORM\Column(name="autor_uuid", type="uuid", nullable=true)
     */
    private $autorId;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="active", type="boolean", nullable=true)
     */
    private $active;

    /**
     * @var string|null
     *
     * @ORM\Column(name="public_hash", type="string", nullable=true)
     */
    private $public;

    /**
     * @var \Domain\Entities\Subscriber\Account
     *
     * @ORM\ManyToOne(targetEntity="Domain\Entities\Subscriber\Account")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="autor_id", referencedColumnName="id")
     * })
     */
    private $autor;

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
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Domain\Entities\Business\Objects\Objects", mappedBy="files")
     */
    private $objects = array();

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Domain\Entities\Business\Objects\Specification", mappedBy="files")
     */
    private $specification = array();

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Domain\Entities\Services\Notification", inversedBy="files")
     * @ORM\JoinTable(name="files_notification",
     *   joinColumns={
     *     @ORM\JoinColumn(name="files_id", referencedColumnName="id", onDelete="CASCADE")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="notification_id", referencedColumnName="id", onDelete="CASCADE")
     *   }
     * )
     */
    private $notification = array();

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Domain\Entities\Business\Document\Workflow", inversedBy="files")
     * @ORM\JoinTable(name="files_workflow",
     *   joinColumns={
     *     @ORM\JoinColumn(name="files_id", referencedColumnName="id", onDelete="CASCADE")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="workflow_id", referencedColumnName="id", onDelete="CASCADE")
     *   }
     * )
     */
    private $workflow = array();

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Domain\Entities\Business\Document\Tasks", inversedBy="files")
     * @ORM\JoinTable(name="files_tasks",
     *   joinColumns={
     *     @ORM\JoinColumn(name="files_id", referencedColumnName="id", onDelete="CASCADE")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="tasks_id", referencedColumnName="id", onDelete="CASCADE")
     *   }
     * )
     */
    private $tasks = array();

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Domain\Entities\Services\Chat", inversedBy="files")
     * @ORM\JoinTable(name="chat_files",
     *   joinColumns={
     *     @ORM\JoinColumn(name="files_id", referencedColumnName="id", onDelete="CASCADE")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="chat_id", referencedColumnName="id", onDelete="CASCADE")
     *   }
     * )
     */
    private $chats = array();

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Domain\Entities\Business\Document\Requisition\Invoice", inversedBy="files")
     * @ORM\JoinTable(name="files_invoice",
     *   joinColumns={
     *     @ORM\JoinColumn(name="files_id", referencedColumnName="id", onDelete="CASCADE")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="invoice_id", referencedColumnName="id", onDelete="CASCADE")
     *   }
     * )
     */
    private $requisitionInvoice = array();

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Domain\Entities\Business\Document\Requisition\Invoice\Material", inversedBy="files")
     * @ORM\JoinTable(name="files_material",
     *   joinColumns={
     *     @ORM\JoinColumn(name="files_id", referencedColumnName="id", onDelete="CASCADE")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="material_id", referencedColumnName="id", onDelete="CASCADE")
     *   }
     * )
     */
    private $requisitionInvoiceMaterial = array();

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Domain\Entities\Business\Document\Contracts", inversedBy="files")
     * @ORM\JoinTable(name="contracts_files",
     *   joinColumns={
     *     @ORM\JoinColumn(name="files_id", referencedColumnName="id", onDelete="CASCADE")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="contracts_id", referencedColumnName="id", onDelete="CASCADE")
     *   }
     * )
     */
    private $contracts = array();

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Domain\Entities\Business\Document\Requisition\Invoice\MaterialReceived", inversedBy="files")
     * @ORM\JoinTable(name="files_material_received",
     *   joinColumns={
     *     @ORM\JoinColumn(name="files_id", referencedColumnName="id", onDelete="CASCADE")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="material_received_id", referencedColumnName="id", onDelete="CASCADE")
     *   }
     * )
     */
    private $requisitionInvoiceMaterialReceived = array();

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
        $this->requisitionInvoice = new \Doctrine\Common\Collections\ArrayCollection();
        $this->requisitionInvoiceMaterial = new \Doctrine\Common\Collections\ArrayCollection();
        $this->contracts = new \Doctrine\Common\Collections\ArrayCollection();
        $this->requisitionInvoiceMaterialReceived = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set name.
     *
     * @param string|null $name
     *
     * @return Files
     */
    public function setName($name = null)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name.
     *
     * @return string|null
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set type.
     *
     * @param string|null $type
     *
     * @return Files
     */
    public function setType($type = null)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type.
     *
     * @return string|null
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set patch.
     *
     * @param string|null $patch
     *
     * @return Files
     */
    public function setPatch($patch = null)
    {
        $this->patch = $patch;

        return $this;
    }

    /**
     * Get patch.
     *
     * @return string|null
     */
    public function getPatch()
    {
        return $this->patch;
    }

    /**
     * Set mime.
     *
     * @param string|null $mime
     *
     * @return Files
     */
    public function setMime($mime = null)
    {
        $this->mime = $mime;

        return $this;
    }

    /**
     * Get mime.
     *
     * @return string|null
     */
    public function getMime()
    {
        return $this->mime;
    }

    /**
     * Set hash.
     *
     * @param string|null $hash
     *
     * @return Files
     */
    public function setHash($hash = null)
    {
        $this->hash = $hash;

        return $this;
    }

    /**
     * Get hash.
     *
     * @return string|null
     */
    public function getHash()
    {
        return $this->hash;
    }

    /**
     * Set size.
     *
     * @param int|null $size
     *
     * @return Files
     */
    public function setSize($size = null)
    {
        $this->size = $size;

        return $this;
    }

    /**
     * Get size.
     *
     * @return int|null
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * Set storage.
     *
     * @param string|null $storage
     *
     * @return Files
     */
    public function setStorage($storage = null)
    {
        $this->storage = $storage;

        return $this;
    }

    /**
     * Get storage.
     *
     * @return string|null
     */
    public function getStorage()
    {
        return $this->storage;
    }

    /**
     * Set createdAt.
     *
     * @param \DateTime $createdAt
     *
     * @return Files
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
     * @return Files
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
     * Set autorId.
     *
     * @param uuid|null $autorId
     *
     * @return Files
     */
    public function setAutorId($autorId = null)
    {
        $this->autorId = $autorId;

        return $this;
    }

    /**
     * Get autorId.
     *
     * @return uuid|null
     */
    public function getAutorId()
    {
        return $this->autorId;
    }

    /**
     * Set active.
     *
     * @param bool|null $active
     *
     * @return Files
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
     * Set public.
     *
     * @param string|null $public
     *
     * @return Files
     */
    public function setPublic($public = null)
    {
        $this->public = $public;

        return $this;
    }

    /**
     * Get public.
     *
     * @return string|null
     */
    public function getPublic()
    {
        return $this->public;
    }

    /**
     * Set autor.
     *
     * @param \Domain\Entities\Subscriber\Account|null $autor
     *
     * @return Files
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
     * Set company.
     *
     * @param \Domain\Entities\Business\Company\Company|null $company
     *
     * @return Files
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
     * Add object.
     *
     * @param \Domain\Entities\Business\Objects\Objects $object
     *
     * @return Files
     */
    public function addObject(\Domain\Entities\Business\Objects\Objects $object)
    {
        $this->objects[] = $object;

        return $this;
    }

    /**
     * Remove object.
     *
     * @param \Domain\Entities\Business\Objects\Objects $object
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeObject(\Domain\Entities\Business\Objects\Objects $object)
    {
        return $this->objects->removeElement($object);
    }

    /**
     * Get objects.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getObjects()
    {
        return $this->objects;
    }

    /**
     * Add specification.
     *
     * @param \Domain\Entities\Business\Objects\Specification $specification
     *
     * @return Files
     */
    public function addSpecification(\Domain\Entities\Business\Objects\Specification $specification)
    {
        $this->specification[] = $specification;

        return $this;
    }

    /**
     * Remove specification.
     *
     * @param \Domain\Entities\Business\Objects\Specification $specification
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeSpecification(\Domain\Entities\Business\Objects\Specification $specification)
    {
        return $this->specification->removeElement($specification);
    }

    /**
     * Get specification.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSpecification()
    {
        return $this->specification;
    }

    /**
     * Add notification.
     *
     * @param \Domain\Entities\Services\Notification $notification
     *
     * @return Files
     */
    public function addNotification(\Domain\Entities\Services\Notification $notification)
    {
        $this->notification[] = $notification;

        return $this;
    }

    /**
     * Remove notification.
     *
     * @param \Domain\Entities\Services\Notification $notification
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeNotification(\Domain\Entities\Services\Notification $notification)
    {
        return $this->notification->removeElement($notification);
    }

    /**
     * Get notification.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getNotification()
    {
        return $this->notification;
    }

    /**
     * Add workflow.
     *
     * @param \Domain\Entities\Business\Document\Workflow $workflow
     *
     * @return Files
     */
    public function addWorkflow(\Domain\Entities\Business\Document\Workflow $workflow)
    {
        $this->workflow[] = $workflow;

        return $this;
    }

    /**
     * Remove workflow.
     *
     * @param \Domain\Entities\Business\Document\Workflow $workflow
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeWorkflow(\Domain\Entities\Business\Document\Workflow $workflow)
    {
        return $this->workflow->removeElement($workflow);
    }

    /**
     * Get workflow.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getWorkflow()
    {
        return $this->workflow;
    }

    /**
     * Add task.
     *
     * @param \Domain\Entities\Business\Document\Tasks $task
     *
     * @return Files
     */
    public function addTask(\Domain\Entities\Business\Document\Tasks $task)
    {
        $this->tasks[] = $task;

        return $this;
    }

    /**
     * Remove task.
     *
     * @param \Domain\Entities\Business\Document\Tasks $task
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeTask(\Domain\Entities\Business\Document\Tasks $task)
    {
        return $this->tasks->removeElement($task);
    }

    /**
     * Get tasks.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTasks()
    {
        return $this->tasks;
    }

    /**
     * Add chat.
     *
     * @param \Domain\Entities\Services\Chat $chat
     *
     * @return Files
     */
    public function addChat(\Domain\Entities\Services\Chat $chat)
    {
        $this->chats[] = $chat;

        return $this;
    }

    /**
     * Remove chat.
     *
     * @param \Domain\Entities\Services\Chat $chat
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeChat(\Domain\Entities\Services\Chat $chat)
    {
        return $this->chats->removeElement($chat);
    }

    /**
     * Get chats.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getChats()
    {
        return $this->chats;
    }

    /**
     * Add requisitionInvoice.
     *
     * @param \Domain\Entities\Business\Document\Requisition\Invoice $requisitionInvoice
     *
     * @return Files
     */
    public function addRequisitionInvoice(\Domain\Entities\Business\Document\Requisition\Invoice $requisitionInvoice)
    {
        $this->requisitionInvoice[] = $requisitionInvoice;

        return $this;
    }

    /**
     * Remove requisitionInvoice.
     *
     * @param \Domain\Entities\Business\Document\Requisition\Invoice $requisitionInvoice
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeRequisitionInvoice(\Domain\Entities\Business\Document\Requisition\Invoice $requisitionInvoice)
    {
        return $this->requisitionInvoice->removeElement($requisitionInvoice);
    }

    /**
     * Get requisitionInvoice.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRequisitionInvoice()
    {
        return $this->requisitionInvoice;
    }

    /**
     * Add requisitionInvoiceMaterial.
     *
     * @param \Domain\Entities\Business\Document\Requisition\Invoice\Material $requisitionInvoiceMaterial
     *
     * @return Files
     */
    public function addRequisitionInvoiceMaterial(\Domain\Entities\Business\Document\Requisition\Invoice\Material $requisitionInvoiceMaterial)
    {
        $this->requisitionInvoiceMaterial[] = $requisitionInvoiceMaterial;

        return $this;
    }

    /**
     * Remove requisitionInvoiceMaterial.
     *
     * @param \Domain\Entities\Business\Document\Requisition\Invoice\Material $requisitionInvoiceMaterial
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeRequisitionInvoiceMaterial(\Domain\Entities\Business\Document\Requisition\Invoice\Material $requisitionInvoiceMaterial)
    {
        return $this->requisitionInvoiceMaterial->removeElement($requisitionInvoiceMaterial);
    }

    /**
     * Get requisitionInvoiceMaterial.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRequisitionInvoiceMaterial()
    {
        return $this->requisitionInvoiceMaterial;
    }

    /**
     * Add contract.
     *
     * @param \Domain\Entities\Business\Document\Contracts $contract
     *
     * @return Files
     */
    public function addContract(\Domain\Entities\Business\Document\Contracts $contract)
    {
        $this->contracts[] = $contract;

        return $this;
    }

    /**
     * Remove contract.
     *
     * @param \Domain\Entities\Business\Document\Contracts $contract
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeContract(\Domain\Entities\Business\Document\Contracts $contract)
    {
        return $this->contracts->removeElement($contract);
    }

    /**
     * Get contracts.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getContracts()
    {
        return $this->contracts;
    }

    /**
     * Add requisitionInvoiceMaterialReceived.
     *
     * @param \Domain\Entities\Business\Document\Requisition\Invoice\MaterialReceived $requisitionInvoiceMaterialReceived
     *
     * @return Files
     */
    public function addRequisitionInvoiceMaterialReceived(\Domain\Entities\Business\Document\Requisition\Invoice\MaterialReceived $requisitionInvoiceMaterialReceived)
    {
        $this->requisitionInvoiceMaterialReceived[] = $requisitionInvoiceMaterialReceived;

        return $this;
    }

    /**
     * Remove requisitionInvoiceMaterialReceived.
     *
     * @param \Domain\Entities\Business\Document\Requisition\Invoice\MaterialReceived $requisitionInvoiceMaterialReceived
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeRequisitionInvoiceMaterialReceived(\Domain\Entities\Business\Document\Requisition\Invoice\MaterialReceived $requisitionInvoiceMaterialReceived)
    {
        return $this->requisitionInvoiceMaterialReceived->removeElement($requisitionInvoiceMaterialReceived);
    }

    /**
     * Get requisitionInvoiceMaterialReceived.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRequisitionInvoiceMaterialReceived()
    {
        return $this->requisitionInvoiceMaterialReceived;
    }
}
