<?php

namespace Domain\Entities\Business;

use Doctrine\ORM\Mapping as ORM;

/**
 * Material
 *
 * @ORM\Table(name="material")
 * @ORM\Entity
 */
class Material
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
     * @var int|null
     *
     * @ORM\Column(name="level", type="integer", nullable=true)
     */
    private $level;

    /**
     * @var int|null
     *
     * @ORM\Column(name="parent", type="integer", nullable=true)
     */
    private $parent;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="is_group", type="boolean", nullable=true)
     */
    private $isGroup;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", nullable=false)
     */
    private $name;

    /**
     * @var string|null
     *
     * @ORM\Column(name="full_name", type="text", nullable=true)
     */
    private $fullname;

    /**
     * @var string|null
     *
     * @ORM\Column(name="articul", type="text", nullable=true)
     */
    private $articul;

    /**
     * @var string|null
     *
     * @ORM\Column(name="type", type="text", nullable=true)
     */
    private $type;

    /**
     * @var int|null
     *
     * @ORM\Column(name="autor_id", type="integer", nullable=true)
     */
    private $autorId;

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
     * @var bool|null
     *
     * @ORM\Column(name="active", type="boolean", nullable=true)
     */
    private $active;


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
     * Set level.
     *
     * @param int|null $level
     *
     * @return Material
     */
    public function setLevel($level = null)
    {
        $this->level = $level;

        return $this;
    }

    /**
     * Get level.
     *
     * @return int|null
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * Set parent.
     *
     * @param int|null $parent
     *
     * @return Material
     */
    public function setParent($parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent.
     *
     * @return int|null
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Set isGroup.
     *
     * @param bool|null $isGroup
     *
     * @return Material
     */
    public function setIsGroup($isGroup = null)
    {
        $this->isGroup = $isGroup;

        return $this;
    }

    /**
     * Get isGroup.
     *
     * @return bool|null
     */
    public function getIsGroup()
    {
        return $this->isGroup;
    }

    /**
     * Set name.
     *
     * @param string $name
     *
     * @return Material
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
     * Set fullname.
     *
     * @param string|null $fullname
     *
     * @return Material
     */
    public function setFullname($fullname = null)
    {
        $this->fullname = $fullname;

        return $this;
    }

    /**
     * Get fullname.
     *
     * @return string|null
     */
    public function getFullname()
    {
        return $this->fullname;
    }

    /**
     * Set articul.
     *
     * @param string|null $articul
     *
     * @return Material
     */
    public function setArticul($articul = null)
    {
        $this->articul = $articul;

        return $this;
    }

    /**
     * Get articul.
     *
     * @return string|null
     */
    public function getArticul()
    {
        return $this->articul;
    }

    /**
     * Set type.
     *
     * @param string|null $type
     *
     * @return Material
     */
    public function setType($type = null)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type.
     *
     * @return string|null
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set autorId.
     *
     * @param int|null $autorId
     *
     * @return Material
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
     * Set createdAt.
     *
     * @param \DateTime $createdAt
     *
     * @return Material
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
     * @return Material
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
     * Set active.
     *
     * @param bool|null $active
     *
     * @return Material
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
}
