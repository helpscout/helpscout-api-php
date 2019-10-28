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
     * @return FieldOption
     */
    public function setId(int $id): self
    {
        Assert::greaterThan($id, 0);

        $this->id = $id;

        return $this;
    }

    /**
     * @return int
     */
    public function getOrder(): int
    {
        return $this->order;
    }

    /**
     * @param int $order
     *
     * @return FieldOption
     */
    public function setOrder(int $order): self
    {
        $this->order = $order;

        return $this;
    }

    /**
     * @return string
     */
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * @param string $label
     *
     * @return FieldOption
     */
    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }
}
