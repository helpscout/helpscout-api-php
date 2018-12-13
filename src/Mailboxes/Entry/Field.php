<?php

declare(strict_types=1);

namespace HelpScout\Api\Mailboxes\Entry;

use HelpScout\Api\Assert\Assert;
use HelpScout\Api\Entity\Collection;
use HelpScout\Api\Entity\Hydratable;

class Field implements Hydratable
{
    const TYPE_DROPDOWN = 'dropdown';

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
    public function getOrder(): int
    {
        return $this->order;
    }

    /**
     * @param int $order
     */
    public function setOrder(int $order)
    {
        $this->order = $order;
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
     */
    public function setRequired(bool $required)
    {
        $this->required = $required;
    }

    /**
     * @return FieldOption[]|Collection
     */
    public function getOptions(): Collection
    {
        return $this->options;
    }
}
