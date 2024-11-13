<?php

namespace Domain\Entities\Business\Objects;

use Doctrine\ORM\Mapping as ORM;

/**
 * Projects
 *
 * @ORM\Table(name="projects")
 * @ORM\Entity
 */
class Projects
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", options={"unsigned"=true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
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
     * @var int|null
     *
     * @ORM\Column(name="autor_id", type="integer", nullable=true)
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
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Domain\Entities\Services\Files", mappedBy="projects")
     */
    private $files;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Domain\Entities\Business\Objects\Specification", mappedBy="projects")
     */
    private $specification;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Domain\Entities\Subscriber\Roles", mappedBy="projects")
     */
    private $roles;

    /**
     * @var \Domain\Entities\Business\Objects\Objects
     *
     * @ORM\ManyToOne(targetEntity="Domain\Entities\Business\Objects\Objects", inversedBy="projects")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="object_id", referencedColumnName="id")
     * })
     */
    private $objects;

    /**
     * @var \Domain\Entities\Subscriber\Account
     *
     * @ORM\ManyToOne(targetEntity="Domain\Entities\Subscriber\Account", inversedBy="projects")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="account_id", referencedColumnName="id")
     * })
     */
    private $account;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Domain\Entities\Business\Objects\Contacts", mappedBy="projects")
     */
    private $contacts = array();

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->files = new \Doctrine\Common\Collections\ArrayCollection();
        $this->specification = new \Doctrine\Common\Collections\ArrayCollection();
        $this->roles = new \Doctrine\Common\Collections\ArrayCollection();
        $this->contacts = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id.
     *
     * @return int
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
     * @return Projects
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
     * @return Projects
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
     * Set createdAt.
     *
     * @param \DateTime $createdAt
     *
     * @return Projects
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
     * @return Projects
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
     * @param int|null $autorId
     *
     * @return Projects
     */
    public function setAutorId($autorId = null)
    {
        $this->autorId = $autorId;

        return $this;
    }

    /**
     * Get autorId.
     *
     * @return int|null
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
     * @return Projects
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
     * @return Projects
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
     * Add file.
     *
     * @param \Domain\Entities\Services\Files $file
     *
     * @return Projects
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
     * Add specification.
     *
     * @param \Domain\Entities\Business\Objects\Specification $specification
     *
     * @return Projects
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
     * Add role.
     *
     * @param \Domain\Entities\Subscriber\Roles $role
     *
     * @return Projects
     */
    public function addRole(\Domain\Entities\Subscriber\Roles $role)
    {
        $this->roles[] = $role;

        return $this;
    }

    /**
     * Remove role.
     *
     * @param \Domain\Entities\Subscriber\Roles $role
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeRole(\Domain\Entities\Subscriber\Roles $role)
    {
        return $this->roles->removeElement($role);
    }

    /**
     * Get roles.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * Set objects.
     *
     * @param \Domain\Entities\Business\Objects\Objects|null $objects
     *
     * @return Projects
     */
    public function setObjects(\Domain\Entities\Business\Objects\Objects $objects = null)
    {
        $this->objects = $objects;

        return $this;
    }

    /**
     * Get objects.
     *
     * @return \Domain\Entities\Business\Objects\Objects|null
     */
    public function getObjects()
    {
        return $this->objects;
    }

    /**
     * Set account.
     *
     * @param \Domain\Entities\Subscriber\Account|null $account
     *
     * @return Projects
     */
    public function setAccount(\Domain\Entities\Subscriber\Account $account = null)
    {
        $this->account = $account;

        return $this;
    }

    /**
     * Get account.
     *
     * @return \Domain\Entities\Subscriber\Account|null
     */
    public function getAccount()
    {
        return $this->account;
    }

    /**
     * Add contact.
     *
     * @param \Domain\Entities\Business\Objects\Contacts $contact
     *
     * @return Projects
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
}
