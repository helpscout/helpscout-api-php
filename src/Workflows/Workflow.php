<?php

declare(strict_types=1);

namespace HelpScout\Api\Workflows;

use HelpScout\Api\Assert\Assert;
use HelpScout\Api\Entity\Extractable;
use HelpScout\Api\Entity\Hydratable;
use HelpScout\Api\Support\ExtractsData;
use HelpScout\Api\Support\HydratesData;

class Workflow implements Hydratable, Extractable
{
    use ExtractsData,
        HydratesData;

    public const TYPE_MANUAL = 'manual';
    public const TYPE_AUTOMATIC = 'automatic';
    public const VALID_TYPES = [
        self::TYPE_MANUAL,
        self::TYPE_AUTOMATIC,
    ];

    /**
     * @var int
     */
    private $id;

    /**
     * @var int
     */
    private $mailboxId;

    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $status;

    /**
     * @var int
     */
    private $order;

    /**
     * @var string
     */
    private $name;

    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var \DateTime
     */
    private $modifiedAt;

    public function hydrate(array $data, array $embedded = [])
    {
        $this->setId($data['id'] ?? null);
        $this->setMailboxId($data['mailboxId'] ?? null);
        $this->setStatus($data['status'] ?? null);
        $this->setType($data['type'] ?? null);
        $this->setOrder($data['order'] ?? null);
        $this->setName($data['name'] ?? null);
        $this->setCreatedAt($data['createdAt'] ?? null);
        $this->setModifiedAt($data['modifiedAt'] ?? null);
    }

    public function extract(): array
    {
        $data = [
            'id' => $this->getId(),
            'mailboxId' => $this->getMailboxId(),
            'status' => $this->getStatus(),
            'type' => $this->getType(),
            'order' => $this->getOrder(),
            'name' => $this->getName(),
        ];

        if ($this->getCreatedAt() !== null) {
            $data['createdAt'] = $this->to8601Utc($this->getCreatedAt());
        }

        if ($this->getModifiedAt() !== null) {
            $data['modifiedAt'] = $this->to8601Utc($this->getModifiedAt());
        }

        return $data;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): Workflow
    {
        $this->id = $id;

        return $this;
    }

    public function getMailboxId(): ?int
    {
        return $this->mailboxId;
    }

    public function setMailboxId(?int $mailboxId): Workflow
    {
        $this->mailboxId = $mailboxId;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): Workflow
    {
        if ($type !== null) {
            Assert::oneOf($type, self::VALID_TYPES);
        }
        $this->type = $type;

        return $this;
    }

    public function isManual(): bool
    {
        return $this->getType() === self::TYPE_MANUAL;
    }

    public function isAutomatic(): bool
    {
        return $this->getType() === self::TYPE_AUTOMATIC;
    }

    public function setManual(): Workflow
    {
        return $this->setType(self::TYPE_MANUAL);
    }

    public function setAutomatic(): Workflow
    {
        return $this->setType(self::TYPE_AUTOMATIC);
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): Workflow
    {
        $this->status = $status;

        return $this;
    }

    public function getOrder(): ?int
    {
        return $this->order;
    }

    public function setOrder(?int $order): Workflow
    {
        $this->order = $order;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): Workflow
    {
        $this->name = $name;

        return $this;
    }

    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    /**
     * @param string|null $createdAt
     */
    public function setCreatedAt($createdAt): Workflow
    {
        $this->createdAt = $this->transformDateTime($createdAt);

        return $this;
    }

    public function getModifiedAt(): ?\DateTime
    {
        return $this->modifiedAt;
    }

    /**
     * @param string|null $modifiedAt
     */
    public function setModifiedAt($modifiedAt): Workflow
    {
        $this->modifiedAt = $this->transformDateTime($modifiedAt);

        return $this;
    }
}
