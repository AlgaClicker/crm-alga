<?php

namespace Domain\Entities;

use Doctrine\ORM\Mapping as ORM;
use Tymon\JWTAuth\Contracts\JWTSubject;
use LaravelDoctrine\ORM\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthContract;

/**
 * @ORM\Entity
 * @ORM\Table(name="users")
 */
class User implements JWTSubject, AuthContract
{

    use Authenticatable;
    
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected $id;
    
    /**
     * @ORM\Column(type="string", length=500, unique=true)
     */
    private $username;
    
    public function __construct($username, $password)
    {
        $this->username = $username;
        $this->password = $password;
    }
    
    /**
     * Get the name of the unique identifier for the user.
     *
     * @return string
     */
    public function getAuthIdentifierName()
    {
        return 'id';
    }
    
    /**
     * Get the unique identifier for the user.
     *
     * @return mixed
     */
    public function getAuthIdentifier()
    {
        return $this->getId();
    }
    
    public function getId()
    {
        return $this->id;
    }
    
    public function setUsername($username)
    {
        $this->username = $username;
    }
    
    public function getUsername()
    {
        return $this->username;
    }
    
    public function setPassword($password)
    {
        $this->password = $password;
    }
    
    public function getPassword()
    {
        return $this->password;
    }
    
    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword()
    {
        return $this->getPassword();
    }
    
    /**
     * Get the token value for the "remember me" session.
     *
     * @return string
     */
    public function getRememberToken()
    {
        // TODO: Implement getRememberToken() method.
    }
    
    /**
     * Set the token value for the "remember me" session.
     *
     * @param  string $value
     * @return void
     */
    public function setRememberToken($value)
    {
        // TODO: Implement setRememberToken() method.
    }
    
    /**
     * Get the column name for the "remember me" token.
     *
     * @return string
     */
    public function getRememberTokenName()
    {
        // TODO: Implement getRememberTokenName() method.
    }
    
    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getId();
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
    
    /**
     * Determine if the entity has a given ability.
     *
     * @param  string $ability
     * @param  array|mixed $arguments
     * @return bool
     */
    public function can($ability, $arguments = [])
    {
        // TODO: Implement can() method.
    }
    
} 