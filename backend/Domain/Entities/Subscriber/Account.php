<?php
namespace Domain\Entities\Subscriber;


use Domain\Entities\Business\Company\Workpeople;
use PhpParser\Node\Scalar\String_;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Doctrine\ORM\Mapping as ORM;
use LaravelDoctrine\ORM\Auth\Authenticatable;
use LaravelDoctrine\ACL\Mappings as ACL;
use LaravelDoctrine\ACL\Roles\HasRoles;
use Illuminate\Contracts\Auth\Authenticatable as AuthContract;
use Illuminate\Auth\Authenticatable as AuthInt;
use LaravelDoctrine\ACL\Contracts\HasRoles as HasRolesContract;
use Illuminate\Support\Facades\Hash;

use DateTime;

class Account  implements JWTSubject,AuthContract, HasRolesContract
{
    use Authenticatable,HasRoles, AuthInt;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", nullable=true)
     */

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", options={"unsigned"=true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;


    /**
     * @var string|null
     *
     * @ORM\Column(name="surname", type="string", nullable=true)
     */
    private $surname;

    /**
     * @var string|null
     *
     * @ORM\Column(name="name", type="string", nullable=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", nullable=false, unique=true)
     */
    private $username;

    /**
     * @var string|null
     *
     * @ORM\Column(name="patronymic", type="string", nullable=true, unique=false)
     */
    private $patronymic;

    /**
     * @var int|null
     *
     * @ORM\Column(name="phone_number", type="integer", nullable=true, unique=false)
     */
    private $phoneNumber;

    /**
     * @var string|null
     *
     * @ORM\Column(name="token", type="text", nullable=true)
     */
    private $token;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", nullable=false, unique=true)
     */
    private $email;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    private DateTime $createdAt;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="last_login", type="datetime", nullable=true)
     */
    private DateTime $lastLogin;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    private DateTime $updatedAt;

    /**
     * @var \stdClass|null
     *
     * @ORM\Column(name="autor_id", type="object", nullable=true)
     */
    private $autorId;

    /**
     * @var bool|null
     *
     * @Serializer\Type("boolean")
     * @ORM\Column(name="active", type="boolean", nullable=true)
     */
    private $active;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="delete", type="boolean", nullable=true)
     */
    private $delete = false;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Domain\Entities\Subscriber\AccountOptions", mappedBy="account")
     */
    private $accountoptions;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="is_admin", type="boolean", nullable=true)
     */
    private $isAdmin = false;

    /**
     * @var \Domain\Entities\Business\Company\Company
     *
     * @ORM\ManyToOne(targetEntity="Domain\Entities\Business\Company\Company", inversedBy="account")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="company_id", referencedColumnName="id")
     * })
     */
    private $company;

    /**
     * @var \Domain\Entities\Subscriber\Roles
     *
     * @ORM\ManyToOne(targetEntity="Domain\Entities\Subscriber\Roles", inversedBy="account")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="role_id", referencedColumnName="id")
     * })
     */
    private $roles;


    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Domain\Entities\Business\Master\Brigade", mappedBy="autor")
     */
    private $brigades;

    private $objects = array();
    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Domain\Entities\Business\Document\Tasks", inversedBy="accounts")
     * @ORM\JoinTable(name="account_tasks",
     *   joinColumns={
     *     @ORM\JoinColumn(name="account_id", referencedColumnName="id", onDelete="CASCADE")
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
     * @ORM\ManyToMany(targetEntity="Domain\Entities\Business\Document\Workflow", mappedBy="responsible")
     */
    private $workflows = [];

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Domain\Entities\Business\Objects\Projects", mappedBy="accounts_access")
     */
    private $specification = array();

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Domain\Entities\Subscriber\AccountTokens", inversedBy="accounts")
     * @ORM\JoinTable(name="account_account_tokens",
     *   joinColumns={
     *     @ORM\JoinColumn(name="account_id", referencedColumnName="id", onDelete="CASCADE")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="account_tokens_id", referencedColumnName="id", onDelete="CASCADE")
     *   }
     * )
     */
    private $accountTokens = array();
    private $workpeople;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->accountoptions = new \Doctrine\Common\Collections\ArrayCollection();
        $this->objects = new \Doctrine\Common\Collections\ArrayCollection();
        $this->workflows = new \Doctrine\Common\Collections\ArrayCollection();
        $this->specification = new \Doctrine\Common\Collections\ArrayCollection();
        $this->tasks = new \Doctrine\Common\Collections\ArrayCollection();
        $this->accountTokens = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set surname.
     *
     * @param string|null $surname
     *
     * @return Account
     */
    public function setSurname($surname = null)
    {
        $this->surname = $surname;

        return $this;
    }

    /**
     * Get surname.
     *
     * @return string|null
     */
    public function getSurname()
    {
        return $this->surname;
    }

    /**
     * Set name.
     *
     * @param string|null $name
     *
     * @return Account
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
     * Set username.
     *
     * @param string $username
     *
     * @return Account
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username.
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set patronymic.
     *
     * @param string|null $patronymic
     *
     * @return Account
     */
    public function setPatronymic($patronymic = null)
    {
        $this->patronymic = $patronymic;

        return $this;
    }

    /**
     * Get patronymic.
     *
     * @return string|null
     */
    public function getPatronymic()
    {
        return $this->patronymic;
    }

    /**
     * Set phoneNumber.
     *
     * @param int|null $phoneNumber
     *
     * @return Account
     */
    public function setPhoneNumber($phoneNumber = null)
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    /**
     * Get phoneNumber.
     *
     * @return int|null
     */
    public function getPhoneNumber()
    {
        return $this->phoneNumber;
    }

    public function getWorkpeople() {
        return $this->workpeople;
    }

    public function setWorkpeople(Workpeople $workpeople=null) {
        $this->workpeople = $workpeople;
    }

    /**
     * Set isAdmin.
     *
     * @param bool|null $isAdmin
     *
     * @return Account
     */
    public function setIsAdmin($isAdmin = null)
    {
        $this->isAdmin = $isAdmin;

        return $this;
    }

    /**
     * Get isAdmin.
     *
     * @return bool|null
     */
    public function getIsAdmin()
    {
        return $this->isAdmin;
    }

    /**
     * Set token.
     *
     * @param string|null $token
     *
     * @return Account
     */
    public function setToken($token = null)
    {
        $this->token = $token;

        return $this;
    }

    /**
     * Get token.
     *
     * @return string|null
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Set email.
     *
     * @param string $email
     *
     * @return Account
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email.
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set createdAt.
     *
     * @param \DateTime $createdAt
     *
     * @return Account
     */
    public function setCreatedAt($createdAt)
    {

        $this->createdAt = $createdAt ;
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
     * Set lastLogin.
     *
     * @param \DateTime|null $lastLogin
     *
     * @return Account
     */
    public function setLastLogin($lastLogin = null)
    {
        $this->lastLogin = $lastLogin;

        return $this;
    }

    /**
     * Get lastLogin.
     *
     * @return \DateTime|null
     */
    public function getLastLogin()
    {
        return $this->lastLogin;
    }

    /**
     * Set updatedAt.
     *
     * @param \DateTime|null $updatedAt
     *
     * @return Account
     */
    public function setUpdatedAt($updatedAt = null)
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    public function setId($uuid)
    {
        $this->id = $uuid;
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
     * @param \stdClass|null $autorId
     *
     * @return Account
     */
    public function setAutorId($autorId = null)
    {
        $this->autorId = $autorId;

        return $this;
    }

    /**
     * Get autorId.
     *
     * @return \stdClass|null
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
     * @return Account
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
     * @return Account
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
     * Add accountoption.
     *
     * @param \Domain\Entities\Subscriber\AccountOptions $accountoption
     *
     * @return Account
     */
    public function addAccountoption(\Domain\Entities\Subscriber\AccountOptions $accountoption)
    {
        $this->accountoptions[] = $accountoption;

        return $this;
    }

    /**
     * Remove accountoption.
     *
     * @param \Domain\Entities\Subscriber\AccountOptions $accountoption
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeAccountoption(\Domain\Entities\Subscriber\AccountOptions $accountoption)
    {
        return $this->accountoptions->removeElement($accountoption);
    }

    /**
     * Get accountoptions.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAccountoptions()
    {
        return $this->accountoptions;
    }

    /**
     * Set company.
     *
     * @param \Domain\Entities\Business\Company\Company|null $company
     *
     * @return Account
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
     * Set roles.
     *
     * @param \Domain\Entities\Subscriber\Roles|null $roles
     *
     * @return Account
     */
    public function setRoles(\Domain\Entities\Subscriber\Roles $roles = null)
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * Get roles.
     *
     * @return \Domain\Entities\Subscriber\Roles|null
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * Add specification.
     *
     * @param \Domain\Entities\Business\Objects\Specification $specification
     *
     * @return Account
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
     * Add workflow.
     *
     * @param \Domain\Entities\Business\Document\Workflow $workflow
     *
     * @return Account
     */
    public function addWorkflow(\Domain\Entities\Business\Document\Workflow $workflow)
    {

        $this->workflows->add($workflow);
        //$workflow->addResponsible($this);
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
        return $this->workflows->removeElement($workflow);
    }

    /**
     * Get workflow.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getWorkflows()
    {
        return $this->workflows;
    }

    /**
     * Add task.
     *
     * @param \Domain\Entities\Business\Document\Tasks $task
     *
     * @return Account
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
     * Add accountToken.
     *
     * @param \Domain\Entities\Subscriber\AccountTokens $accountToken
     *
     * @return Account
     */
    public function addAccountToken(\Domain\Entities\Subscriber\AccountTokens $accountToken)
    {
        $this->accountTokens[] = $accountToken;

        return $this;
    }

    /**
     * Add brigade.
     *
     * @param \Domain\Entities\Business\Master\Brigade $brigade
     *
     * @return Account
     */
    public function addBrigade(\Domain\Entities\Business\Master\Brigade $brigade)
    {
        $this->brigades[] = $brigade;

        return $this;
    }

    /**
     * Remove brigade.
     *
     * @param \Domain\Entities\Business\Master\Brigade $brigade
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeBrigade(\Domain\Entities\Business\Master\Brigade $brigade)
    {
        return $this->brigades->removeElement($brigade);
    }

    /**
     * Get brigades.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getBrigades()
    {
        return $this->brigades;
    }

    /**
     * Remove accountToken.
     *
     * @param \Domain\Entities\Subscriber\AccountTokens $accountToken
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeAccountToken(\Domain\Entities\Subscriber\AccountTokens $accountToken)
    {
        return $this->accountTokens->removeElement($accountToken);
    }

    /**
     * Get accountTokens.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAccountTokens()
    {
        return $this->accountTokens;
    }
    /**
     * Add object.
     *
     * @param \Domain\Entities\Business\Objects\Objects $object
     *
     * @return Account
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

    public function setPassword($password)
    {
        $this->password = Hash::make($password);

        return $this;
    }

    /**
     * Get password.
     *
     * @return string|null
     */
    public function getPassword()
    {
        return $this->password;
    }
    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function getJWTIdentifier()
    {
        return $this->getId();
    }

    public function getAuthIdentifierName() {
        return $this->getName();
    }
    public function getAuthIdentifier()
    {
        // TODO: Implement getAuthIdentifier() method.
        return $this->getId();
    }
    public function getAuthPassword()
    {
        return $this->getPassword();
    }
    public function getRememberToken()
    {
        return $this->getToken();
    }
    public function getAuthIdentifierForBroadcasting()
    {
        return $this->getId();
    }

    public function setRememberToken($rememberToken)
    {
        $this->rememberToken =$rememberToken;
        return $this;
    }
    public function getRememberTokenName()
    {
        // TODO: Implement getRememberTokenName() method.
    }

    /**
     * @param string $rememberTokenName
     */
    public function setRememberTokenName(string $rememberTokenName): void
    {
        $this->rememberTokenName = $rememberTokenName;
    }

    public function getWorkpeopleName(): ?String {
        if ($this->workpeople) {
            return $this->workpeople->getName()." ".$this->workpeople->getSurname()." ".$this->workpeople->getPatronymic();

        }
        return "";
    }

}
