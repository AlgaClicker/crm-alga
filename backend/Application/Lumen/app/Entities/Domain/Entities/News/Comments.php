<?php

namespace Domain\Entities\News;

/**
 * Comments
 */
class Comments
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
     * @var \DateTime|null
     */
    private $createdAt;

    /**
     * @var \DateTime|null
     */
    private $updatedAt;

    /**
     * @var bool|null
     */
    private $active;

    /**
     * @var bool|null
     */
    private $delete = false;

    /**
     * @var \Domain\Entities\News\News
     */
    private $news;

    /**
     * @var \Domain\Entities\Subscriber\Account
     */
    private $autor;


}
