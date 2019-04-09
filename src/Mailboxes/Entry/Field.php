<?php

declare(strict_types=1);

namespace HelpScout\Api\Mailboxes\Entry;

use HelpScout\Api\Assert\Assert;
use HelpScout\Api\Entity\Collection;
use HelpScout\Api\Entity\Hydratable;

class Field implements Hydratable
{
    public const TYPE_DROPDOWN = 'dropdown';

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
    private $order;

    /**
     * @var bool
     */
    private $required;

    /**
     * @var FieldOption[]|Collection
     */
    private $options;

    public function __construct()
    {
        $this->options = new Collection();
    }

    public function hydrate(array $data, array $embedded = [])
    {
        $this->setId($data['id'] ?? null);
        $this->setName($data['name'] ?? null);
        $this->setType($data['type'] ?? null);
        $this->setOrder($data['order'] ?? null);
        $this->setRequired($data['required'] ?? null);

        if (isset($data['options'])) {
            foreach ($data['options'] as $optionData) {
                $option = new FieldOption();
                $option->hydrate($optionData);
                $this->options[] = $option;
            }
        }
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
     * @return Field
     */
    public function setId(int $id): Field
    {
        Assert::greaterThan($id, 0);

        $this->id = $id;

        return $this;
    }

    /**
     * @return ?string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return Field
     */
    public function setName(string $name): Field
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
     * @return Field
     */
    public function setType(string $type): Field
    {
        $this->type = $type;

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
     * @return Field
     */
    public function setOrder(int $order): Field
    {
        $this->order = $order;

        return $this;
    }

    /**
     * @return bool
     */
    public function isRequired(): bool
    {
        return $this->required;
    }

    /**
     * @param bool $required
     *
     * @return Field
     */
    public function setRequired(bool $required): Field
    {
        $this->required = $required;

        return $this;
    }

    /**
     * @return FieldOption[]|Collection
     */
    public function getOptions(): Collection
    {
        return $this->options;
    }

    public function setOptions(Collection $options): Field
    {
        $this->options = $options;

        return $this;
    }

    public function addOption(FieldOption $option): Field
    {
        $this->getOptions()->append($option);

        return $this;
    }
}
