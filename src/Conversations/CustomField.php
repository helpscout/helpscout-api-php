<?php

declare(strict_types=1);

namespace HelpScout\Api\Conversations;

use HelpScout\Api\Assert\Assert;
use HelpScout\Api\Entity\Extractable;
use HelpScout\Api\Entity\Hydratable;

class CustomField implements Extractable, Hydratable
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string|null
     */
    private $name;

    /**
     * @var string|null
     */
    private $value;

    public function hydrate(array $data, array $embedded = [])
    {
        if (isset($data['id'])) {
            $this->setId((int) $data['id']);
        }

        $this->setName($data['name'] ?? null);
        $this->setValue($data['value'] ?? null);
    }

    /**
     * {@inheritdoc}
     */
    public function extract(): array
    {
        $data = [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'value' => $this->getValue(),
        ];

        if ($this->getValue() instanceof \DateTimeInterface) {
            $data['value'] = $this->getValue()->format('Y-m-d');
        }

        return $data;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId(int $id)
    {
        Assert::greaterThan($id, 0);

        $this->id = $id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param mixed $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }
}
