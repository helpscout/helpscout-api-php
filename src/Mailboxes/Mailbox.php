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

    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     *
     * @return Mailbox
     */
    public function setId($id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return DateTime|null
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @return Mailbox
     */
    public function setCreatedAt(DateTime $createdAt = null): self
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
     * @return Mailbox
     */
    public function setUpdatedAt(DateTime $updatedAt = null): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return Mailbox
     */
    public function setName($name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    /**
     * @param string|null $slug
     *
     * @return Mailbox
     */
    public function setSlug($slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string|null $email
     *
     * @return Mailbox
     */
    public function setEmail($email): self
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
    public function setFields(Collection $fields): self
    {
        $this->fields = $fields;

        return $this;
    }

    public function addField(Field $field): self
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
    public function setFolders(Collection $folders): self
    {
        $this->folders = $folders;

        return $this;
    }

    public function addFolder(Folder $folder): self
    {
        $this->getFolders()->append($folder);

        return $this;
    }
}
