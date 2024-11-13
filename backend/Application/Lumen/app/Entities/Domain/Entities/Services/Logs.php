<?php

namespace Domain\Entities\Services;

/**
 * Logs
 */
class Logs
{
    /**
     * @var uuid
     */
    private $id;

    /**
     * @var string|null
     */
    private $method;

    /**
     * @var \stdClass|null
     */
    private $entity;

    /**
     * @var string|null
     */
    private $entityId;

    /**
     * @var uuid|null
     */
    private $entityUuid;

    /**
     * @var \stdClass|null
     */
    private $data;

    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var uuid|null
     */
    private $autorId;


}
