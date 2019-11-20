<?php

declare(strict_types=1);

namespace HelpScout\Api\Mailboxes\Entry;

use DateTime;
use HelpScout\Api\Assert\Assert;
use HelpScout\Api\Entity\Hydratable;

class Folder implements Hydratable
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $type;

    /**
     * @var int
     */
    private $userId;

    /**
     * @var int
     */
    private $totalCount;

    /**
     * @var int
     */
    private $activeCount;

    /**
     * @var DateTime
     */
    private $updatedAt;

    public function hydrate(array $data, array $embedded = [])
    {
        $this->setId($data['id'] ?? null);
        $this->setName($data['name'] ?? null);
        $this->setType($data['type'] ?? null);
        $this->setUserId($data['userId'] ?? null);
        $this->setTotalCount($data['totalCount'] ?? null);
        $this->setActiveCount($data['activeCount'] ?? null);
        $this->setUpdatedAt(new DateTime($data['updatedAt'] ?? null));
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): Folder
    {
        Assert::greaterThan($id, 0);

        $this->id = $id;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): Folder
    {
        $this->name = $name;

        return $this;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): Folder
    {
        $this->type = $type;

        return $this;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function setUserId(int $userId): Folder
    {
        $this->userId = $userId;

        return $this;
    }

    public function getTotalCount(): int
    {
        return $this->totalCount;
    }

    public function setTotalCount(int $totalCount): Folder
    {
        $this->totalCount = $totalCount;

        return $this;
    }

    public function getActiveCount(): int
    {
        return $this->activeCount;
    }

    public function setActiveCount(int $activeCount): Folder
    {
        $this->activeCount = $activeCount;

        return $this;
    }

    public function getUpdatedAt(): DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(DateTime $updatedAt): Folder
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
