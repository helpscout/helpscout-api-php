<?php

declare(strict_types=1);

namespace HelpScout\Api\Workflows;

use HelpScout\Api\Assert\Assert;
use HelpScout\Api\Entity\Extractable;
use HelpScout\Api\Entity\Hydratable;
use HelpScout\Api\Support\HydratesData;

class Workflow implements Hydratable, Extractable
{
    use HydratesData;

    public const VALID_TYPES = [
        'manual',
        'automatic',
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

    /**
     * @param array $data
     * @param array $embedded
     */
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

    /**
     * @return array
     */
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
            $data['createdAt'] = $this->getCreatedAt()->format(Extractable::DATETIME_FORMAT);
        }

        if ($this->getModifiedAt() !== null) {
            $data['modifiedAt'] = $this->getModifiedAt()->format(Extractable::DATETIME_FORMAT);
        }

        return $data;
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     */
    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return int|null
     */
    public function getMailboxId(): ?int
    {
        return $this->mailboxId;
    }

    /**
     * @param int|null $mailboxId
     */
    public function setMailboxId(?int $mailboxId): void
    {
        $this->mailboxId = $mailboxId;
    }

    /**
     * @return string|null
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @param string|null $type
     */
    public function setType(?string $type): void
    {
        if ($type !== null) {
            Assert::oneOf($type, self::VALID_TYPES);
        }
        $this->type = $type;
    }

    /**
     * @return string|null
     */
    public function getStatus(): ?string
    {
        return $this->status;
    }

    /**
     * @param string|null $status
     */
    public function setStatus(?string $status): void
    {
        $this->status = $status;
    }

    /**
     * @return int|null
     */
    public function getOrder(): ?int
    {
        return $this->order;
    }

    /**
     * @param int|null $order
     */
    public function setOrder(?int $order): void
    {
        $this->order = $order;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     */
    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return \DateTime|null
     */
    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    /**
     * @param null|string $createdAt
     */
    public function setCreatedAt($createdAt): void
    {
        $this->createdAt = $this->transformDateTime($createdAt);
    }

    /**
     * @return \DateTime|null
     */
    public function getModifiedAt(): ?\DateTime
    {
        return $this->modifiedAt;
    }

    /**
     * @param null|string $modifiedAt
     */
    public function setModifiedAt($modifiedAt): void
    {
        $this->modifiedAt = $this->transformDateTime($modifiedAt);
    }
}
