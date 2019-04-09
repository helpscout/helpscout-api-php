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

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     *
     * @return Folder
     */
    public function setId(int $id): Folder
    {
        Assert::greaterThan($id, 0);

        $this->id = $id;

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
     * @return Folder
     */
    public function setName(string $name): Folder
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     *
     * @return Folder
     */
    public function setType(string $type): Folder
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * @param int $userId
     *
     * @return Folder
     */
    public function setUserId(int $userId): Folder
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * @return int
     */
    public function getTotalCount(): int
    {
        return $this->totalCount;
    }

    /**
     * @param int $totalCount
     *
     * @return Folder
     */
    public function setTotalCount(int $totalCount): Folder
    {
        $this->totalCount = $totalCount;

        return $this;
    }

    /**
     * @return int
     */
    public function getActiveCount(): int
    {
        return $this->activeCount;
    }

    /**
     * @param int $activeCount
     *
     * @return Folder
     */
    public function setActiveCount(int $activeCount): Folder
    {
        $this->activeCount = $activeCount;

        return $this;
    }

    /**
     * @return DateTime
     */
    public function getUpdatedAt(): DateTime
    {
        return $this->updatedAt;
    }

    /**
     * @param DateTime $updatedAt
     *
     * @return Folder
     */
    public function setUpdatedAt(DateTime $updatedAt): Folder
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
