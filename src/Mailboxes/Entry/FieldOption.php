<?php

declare(strict_types=1);

namespace HelpScout\Api\Mailboxes\Entry;

use HelpScout\Api\Assert\Assert;
use HelpScout\Api\Entity\Hydratable;

class FieldOption implements Hydratable
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var int
     */
    private $order;

    /**
     * @var string
     */
    private $label;

    public function hydrate(array $data, array $embedded = [])
    {
        $this->setId($data['id'] ?? null);
        $this->setOrder($data['order'] ?? null);
        $this->setLabel($data['label'] ?? null);
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        Assert::greaterThan($id, 0);

        $this->id = $id;

        return $this;
    }

    public function getOrder(): int
    {
        return $this->order;
    }

    public function setOrder(int $order): self
    {
        $this->order = $order;

        return $this;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }
}
