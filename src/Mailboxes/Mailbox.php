<?php

declare(strict_types=1);

namespace HelpScout\Api\Mailboxes;

use DateTime;
use HelpScout\Api\Entity\Collection;
use HelpScout\Api\Entity\Hydratable;
use HelpScout\Api\Mailboxes\Entry\Field;
use HelpScout\Api\Mailboxes\Entry\Folder;
use HelpScout\Api\Support\HydratesData;

class Mailbox implements Hydratable
{
    use HydratesData;

    /**
     * @var int
     */
    private $id;

    /**
     * @var DateTime|null
     */
    private $createdAt;

    /**
     * @var DateTime|null
     */
    private $updatedAt;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $slug;

    /**
     * @var string
     */
    private $email;

    /**
     * @var Field[]|Collection
     */
    private $fields;

    /**
     * @var Folder[]|Collection
     */
    private $folders;

    public function __construct()
    {
        $this->fields = new Collection();
        $this->folders = new Collection();
    }

    public function hydrate(array $data, array $embedded = [])
    {
        $this->setId($data['id'] ?? null);
        $this->setCreatedAt($this->transformDateTime($data['createdAt'] ?? null));
        $this->setUpdatedAt($this->transformDateTime($data['updatedAt'] ?? null));
        $this->setName($data['name'] ?? null);
        $this->setSlug($data['slug'] ?? null);
        $this->setEmail($data['email'] ?? null);
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return DateTime|null
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param DateTime|null $createdAt
     *
     * @return Mailbox
     */
    public function setCreatedAt(DateTime $createdAt = null): Mailbox
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return DateTime|null
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param DateTime|null $updatedAt
     *
     * @return Mailbox
     */
    public function setUpdatedAt(DateTime $updatedAt = null): Mailbox
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return Mailbox
     */
    public function setName($name): Mailbox
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getSlug(): string
    {
        return $this->slug;
    }

    /**
     * @param string|null $slug
     *
     * @return Mailbox
     */
    public function setSlug($slug): Mailbox
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string|null $email
     *
     * @return Mailbox
     */
    public function setEmail($email): Mailbox
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return Field[]|Collection
     */
    public function getFields(): Collection
    {
        return $this->fields;
    }

    /**
     * @param Field[]|Collection $fields
     *
     * @return Mailbox
     */
    public function setFields(Collection $fields): Mailbox
    {
        $this->fields = $fields;

        return $this;
    }

    public function addField(Field $field): Mailbox
    {
        $this->getFields()->append($field);

        return $this;
    }

    /**
     * @return Folder[]|Collection
     */
    public function getFolders(): Collection
    {
        return $this->folders;
    }

    /**
     * @param Folder[]|Collection $folders
     *
     * @return Mailbox
     */
    public function setFolders(Collection $folders): Mailbox
    {
        $this->folders = $folders;

        return $this;
    }

    public function addFolder(Folder $folder): Mailbox
    {
        $this->getFolders()->append($folder);

        return $this;
    }
}
