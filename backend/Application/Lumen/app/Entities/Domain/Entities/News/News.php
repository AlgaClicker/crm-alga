<?php

namespace Domain\Entities\News;

/**
 * News
 */
class News
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $title;

    /**
     * @var string|null
     */
    private $imageUrl;

    /**
     * @var string
     */
    private $announcement;

    /**
     * @var string
     */
    private $body;

    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var \DateTime|null
     */
    private $updatedAt;

    /**
     * @var string|null
     */
    private $autorId;

    /**
     * @var bool|null
     */
    private $active;

    /**
     * @var bool|null
     */
    private $delete = false;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $comments;

    /**
     * @var \Domain\Entities\Business\Company\Company
     */
    private $company;

    /**
     * @var \Domain\Entities\Subscriber\Account
     */
    private $autor;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->comments = new \Doctrine\Common\Collections\ArrayCollection();
    }

}
