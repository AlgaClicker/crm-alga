<?php

namespace Domain\Entities\Business\Objects;

use Doctrine\ORM\Mapping as ORM;

/**
 * Objects
 *
 * @ORM\Table(name="objects")
 * @ORM\Entity
 */
class Objects
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
     * @ORM\Column(name="name", type="string", nullable=false)
     */
    private $name;

    /**
     * @var string|null
     *
     * @ORM\Column(name="address", type="text", nullable=true)
     */
    private $address;

    /**
     * @var string|null
     *
     * @ORM\Column(name="status", type="string", nullable=true)
     */
    private $status;

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
     * @var bool|null
     *
     * @ORM\Column(name="draft", type="boolean", nullable=true)
     */
    private $draft;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="delete", type="boolean", nullable=true)
     */
    private $delete = false;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="deleted_at", type="datetime", nullable=true)
     */
    private $deletedAt;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="archive_at", type="datetime", nullable=true)
     */
    private $archiveAt;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Domain\Entities\Business\Objects\Specification", mappedBy="object")
     */
    private $specification;

    /**
     * @var \Domain\Entities\Business\Company\Company
     *
     * @ORM\ManyToOne(targetEntity="Domain\Entities\Business\Company\Company", inversedBy="objects")
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
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Domain\Entities\Business\Objects\Contacts", mappedBy="objects")
     */
    private $contacts = array();

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Domain\Entities\Services\Files", inversedBy="objects")
     * @ORM\JoinTable(name="files_objects",
     *   joinColumns={
     *     @ORM\JoinColumn(name="objects_id", referencedColumnName="id", onDelete="CASCADE")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="files_id", referencedColumnName="id", onDelete="CASCADE")
     *   }
     * )
     */
    private $files = array();

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Domain\Entities\Subscriber\Account", mappedBy="objects")
     */
    private $accounts = array();

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Domain\Entities\Subscriber\Account")
     * @ORM\JoinTable(name="object_responsible",
     *   joinColumns={
     *     @ORM\JoinColumn(name="object_id", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="account_id", referencedColumnName="id")
     *   }
     * )
     */
    private $responsibles = array();

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->specification = new \Doctrine\Common\Collections\ArrayCollection();
        $this->contacts = new \Doctrine\Common\Collections\ArrayCollection();
        $this->files = new \Doctrine\Common\Collections\ArrayCollection();
        $this->accounts = new \Doctrine\Common\Collections\ArrayCollection();
        $this->responsibles = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @param string $name
     *
     * @return Objects
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set address.
     *
     * @param string|null $address
     *
     * @return Objects
     */
    public function setAddress($address = null)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address.
     *
     * @return string|null
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set status.
     *
     * @param string|null $status
     *
     * @return Objects
     */
    public function setStatus($status = null)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status.
     *
     * @return string|null
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set createdAt.
     *
     * @param \DateTime $createdAt
     *
     * @return Objects
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
     * @return Objects
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
     * @return Objects
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
     * @return Objects
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
     * Set draft.
     *
     * @param bool|null $draft
     *
     * @return Objects
     */
    public function setDraft($draft = null)
    {
        $this->draft = $draft;

        return $this;
    }

    /**
     * Get draft.
     *
     * @return bool|null
     */
    public function getDraft()
    {
        return $this->draft;
    }

    /**
     * Set delete.
     *
     * @param bool|null $delete
     *
     * @return Objects
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
     * Set deletedAt.
     *
     * @param \DateTime|null $deletedAt
     *
     * @return Objects
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
     * Set archiveAt.
     *
     * @param \DateTime|null $archiveAt
     *
     * @return Objects
     */
    public function setArchiveAt($archiveAt = null)
    {
        $this->archiveAt = $archiveAt;

        return $this;
    }

    /**
     * Get archiveAt.
     *
     * @return \DateTime|null
     */
    public function getArchiveAt()
    {
        return $this->archiveAt;
    }

    /**
     * Add specification.
     *
     * @param \Domain\Entities\Business\Objects\Specification $specification
     *
     * @return Objects
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
     * Set company.
     *
     * @param \Domain\Entities\Business\Company\Company|null $company
     *
     * @return Objects
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
     * @return Objects
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
     * Add contact.
     *
     * @param \Domain\Entities\Business\Objects\Contacts $contact
     *
     * @return Objects
     */
    public function addContact(\Domain\Entities\Business\Objects\Contacts $contact)
    {
        $this->contacts[] = $contact;

        return $this;
    }

    /**
     * Remove contact.
     *
     * @param \Domain\Entities\Business\Objects\Contacts $contact
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeContact(\Domain\Entities\Business\Objects\Contacts $contact)
    {
        return $this->contacts->removeElement($contact);
    }

    /**
     * Get contacts.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getContacts()
    {
        return $this->contacts;
    }

    /**
     * Add file.
     *
     * @param \Domain\Entities\Services\Files $file
     *
     * @return Objects
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

    /**
     * Add account.
     *
     * @param \Domain\Entities\Subscriber\Account $account
     *
     * @return Objects
     */
    public function addAccount(\Domain\Entities\Subscriber\Account $account)
    {
        $this->accounts[] = $account;

        return $this;
    }

    /**
     * Remove account.
     *
     * @param \Domain\Entities\Subscriber\Account $account
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeAccount(\Domain\Entities\Subscriber\Account $account)
    {
        return $this->accounts->removeElement($account);
    }

    /**
     * Get accounts.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAccounts()
    {
        return $this->accounts;
    }

    /**
     * Add responsible.
     *
     * @param \Domain\Entities\Subscriber\Account $responsible
     *
     * @return Objects
     */
    public function addResponsible(\Domain\Entities\Subscriber\Account $responsible)
    {
        $this->responsibles[] = $responsible;

        return $this;
    }

    /**
     * Remove responsible.
     *
     * @param \Domain\Entities\Subscriber\Account $responsible
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeResponsible(\Domain\Entities\Subscriber\Account $responsible)
    {
        return $this->responsibles->removeElement($responsible);
    }

    /**
     * Get responsibles.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getResponsibles()
    {
        return $this->responsibles;
    }
}
