<?php

namespace Domain\Entities\Subscriber;

use Doctrine\ORM\Mapping as ORM;

/**
 * AccountTokens
 *
 * @ORM\Table(name="account_tokens")
 * @ORM\Entity
 */
class AccountTokens
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
     * @ORM\Column(name="token", type="text", nullable=true)
     */
    private $token;

    /**
     * @var string|null
     *
     * @ORM\Column(name="device", type="text", nullable=true)
     */
    private $device;

    /**
     * @var string|null
     *
     * @ORM\Column(name="client", type="text", nullable=true)
     */
    private $client;

    /**
     * @var string|null
     *
     * @ORM\Column(name="host", type="string", nullable=true)
     */
    private $host;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    private $createdAt;

    /**
     * @var int|null
     *
     * @ORM\Column(name="expires_in", type="integer", nullable=true)
     */
    private $expiresIn;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @var \Domain\Entities\Subscriber\Account
     *
     * @ORM\ManyToOne(targetEntity="Domain\Entities\Subscriber\Account", inversedBy="accountTokens")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="account_id", referencedColumnName="id")
     * })
     */
    private $account;


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
     * Set token.
     *
     * @param string|null $token
     *
     * @return AccountTokens
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
     * Set device.
     *
     * @param string|null $device
     *
     * @return AccountTokens
     */
    public function setDevice($device = null)
    {
        $this->device = $device;

        return $this;
    }

    /**
     * Get device.
     *
     * @return string|null
     */
    public function getDevice()
    {
        return $this->device;
    }

    /**
     * Set client.
     *
     * @param string|null $client
     *
     * @return AccountTokens
     */
    public function setClient($client = null)
    {
        $this->client = $client;

        return $this;
    }

    /**
     * Get client.
     *
     * @return string|null
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * Set host.
     *
     * @param string|null $host
     *
     * @return AccountTokens
     */
    public function setHost($host = null)
    {
        $this->host = $host;

        return $this;
    }

    /**
     * Get host.
     *
     * @return string|null
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * Set createdAt.
     *
     * @param \DateTime $createdAt
     *
     * @return AccountTokens
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
     * Set expiresIn.
     *
     * @param int|null $expiresIn
     *
     * @return AccountTokens
     */
    public function setExpiresIn($expiresIn = null)
    {
        $this->expiresIn = $expiresIn;

        return $this;
    }

    /**
     * Get expiresIn.
     *
     * @return int|null
     */
    public function getExpiresIn()
    {
        return $this->expiresIn;
    }

    /**
     * Set updatedAt.
     *
     * @param \DateTime|null $updatedAt
     *
     * @return AccountTokens
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
     * Set account.
     *
     * @param \Domain\Entities\Subscriber\Account|null $account
     *
     * @return AccountTokens
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
}
