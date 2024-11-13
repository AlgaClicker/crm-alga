<?php
namespace Domain\Entities\Subscriber;


use Doctrine\ORM\Mapping as ORM;
use Tymon\JWTAuth\Contracts\JWTSubject;
use LaravelDoctrine\ORM\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthContract;
use Illuminate\Support\Facades\Hash;
use Domain\Entities\Buisnes\Company;
use Domain\Entities\News\News;

class AccountProxy 
{
    
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $username;

    /**
     * @var string|null
     */
    protected $token;

    /**
     * @var string
     */
    private $email;

    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var \DateTime|null
     */
    private $updatedAt;

    /**
     * @var int|null
     */
    private $autorId;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $company;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $news;

    /**
     * Constructor
     */
    public function __construct()
    {
       // $this->company = new \Doctrine\Common\Collections\ArrayCollection();
       // $this->news = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set rememberToken.
     *
     * @param string|null $rememberToken
     *
     * @return Account
     */
    public function setRememberToken($rememberToken = null)
    {
        $this->rememberToken = $rememberToken;

        return $this;
    }

    /**
     * Get rememberToken.
     *
     * @return string|null
     */
    public function getRememberToken()
    {
        return $this->rememberToken;
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
    public function setCreatedAt()
    {
        $this->createdAt = new \DateTime();

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
     * @return Account
     */
    public function setUpdatedAt($updatedAt=null)
    {
        
        $this->updatedAt = new \DateTime();

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
     * @return int|null
     */
    public function getAutorId()
    {
        return $this->autorId;
    }

    /**
     * Add company.
     *
     * @param \Domain\Entities\Buisnes\Company $company
     *
     * @return Account
     */
    public function addCompany(Company $company)
    {
        $this->company[] = $company;

        return $this;
    }

    /**
     * Remove company.
     *
     * @param \Domain\Entities\Buisnes\Company $company
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeCompany(Company $company)
    {
        return $this->company->removeElement($company);
    }

    /**
     * Get company.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * Add news.
     *
     * @param News $news
     *
     * @return Account
     */
    public function addNews(News $news)
    {
        $this->news[] = $news;

        return $this;
    }

    /**
     * Remove news.
     *
     * @param News $news
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeNews(News $news)
    {
        return $this->news->removeElement($news);
    }

    /**
     * Get news.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getNews()
    {
        return $this->news;
    }

    public function getJWTIdentifier()
    {
        return $this->getid();
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
}
