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
     */
    public function setId(int $id)
    {
        Assert::greaterThan($id, 0);

        $this->id = $id;
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
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType(string $type)
    {
        $this->type = $type;
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
     */
    public function setUserId(int $userId)
    {
        $this->userId = $userId;
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
     */
    public function setTotalCount(int $totalCount)
    {
        $this->totalCount = $totalCount;
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
     */
    public function setActiveCount(int $activeCount)
    {
        $this->activeCount = $activeCount;
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
     */
    public function setUpdatedAt(DateTime $updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }
}
