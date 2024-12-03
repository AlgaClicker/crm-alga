<?php

namespace Domain\Entities\Services;

use Doctrine\ORM\Mapping as ORM;

/**
 * Logs
 *
 * @ORM\Table(name="logs")
 * @ORM\Entity(repositoryClass="Infrastructure\Repositories\Services\LogsRepository")
 */
class Logs
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
     * @ORM\Column(name="method", type="string", nullable=true)
     */
    private $method;

    /**
     * @var \stdClass|null
     *
     * @ORM\Column(name="entity", type="object", nullable=true)
     */
    private $entity;

    /**
     * @var string|null
     *
     * @ORM\Column(name="entity_id", type="string", nullable=true)
     */
    private $entityId;

    /**
     * @var uuid|null
     *
     * @ORM\Column(name="entity_uuid", type="uuid", nullable=true)
     */
    private $entityUuid;

    /**
     * @var \stdClass|null
     *
     * @ORM\Column(name="data", type="object", nullable=true)
     */
    private $data;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    private $createdAt;

    /**
     * @var uuid|null
     *
     * @ORM\Column(name="autor_uuid", type="uuid", nullable=true)
     */
    private $autorId;


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
     * Set method.
     *
     * @param string|null $method
     *
     * @return Logs
     */
    public function setMethod($method = null)
    {
        $this->method = $method;

        return $this;
    }

    /**
     * Get method.
     *
     * @return string|null
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * Set entity.
     *
     * @param \stdClass|null $entity
     *
     * @return Logs
     */
    public function setEntity($entity = null)
    {
        $this->entity = $entity;

        return $this;
    }

    /**
     * Get entity.
     *
     * @return \stdClass|null
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * Set entityId.
     *
     * @param string|null $entityId
     *
     * @return Logs
     */
    public function setEntityId($entityId = null)
    {
        $this->entityId = $entityId;

        return $this;
    }

    /**
     * Get entityId.
     *
     * @return string|null
     */
    public function getEntityId()
    {
        return $this->entityId;
    }

    /**
     * Set entityUuid.
     *
     * @param uuid|null $entityUuid
     *
     * @return Logs
     */
    public function setEntityUuid($entityUuid = null)
    {
        $this->entityUuid = $entityUuid;

        return $this;
    }

    /**
     * Get entityUuid.
     *
     * @return uuid|null
     */
    public function getEntityUuid()
    {
        return $this->entityUuid;
    }

    /**
     * Set data.
     *
     * @param \stdClass|null $data
     *
     * @return Logs
     */
    public function setData($data = null)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Get data.
     *
     * @return \stdClass|null
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Set createdAt.
     *
     * @param \DateTime $createdAt
     *
     * @return Logs
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
     * Set autorId.
     *
     * @param uuid|null $autorId
     *
     * @return Logs
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
}
