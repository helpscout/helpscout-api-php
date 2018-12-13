<?php

declare(strict_types=1);

namespace HelpScout\Api\Mailboxes;

use DateTime;
use HelpScout\Api\Assert\Assert;
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
     * @param int $id
     */
    public function setId(int $id)
    {
        Assert::greaterThan($id, 0);

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
     */
    public function setCreatedAt(DateTime $createdAt = null)
    {
        $this->createdAt = $createdAt;
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
     */
    public function setUpdatedAt(DateTime $updatedAt = null)
    {
        $this->updatedAt = $updatedAt;
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
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getSlug(): string
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     */
    public function setSlug(string $slug)
    {
        $this->slug = $slug;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email)
    {
        $this->email = $email;
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
     */
    public function setFields(Collection $fields)
    {
        $this->fields = $fields;
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
     */
    public function setFolders(Collection $folders)
    {
        $this->folders = $folders;
    }
}
