<?php

declare(strict_types=1);

namespace HelpScout\Api\Customers\Entry;

use HelpScout\Api\Assert\Assert;
use HelpScout\Api\Entity\Extractable;
use HelpScout\Api\Entity\Hydratable;

class Email implements Extractable, Hydratable
{
    /**
     * @var int|null
     */
    private $id;

    /**
     * @var string|null
     */
    private $value;

    /**
     * @var string|null
     */
    private $type;

    public function hydrate(array $data, array $embedded = [])
    {
        if (isset($data['id'])) {
            $this->setId($data['id']);
        }

        $this->setValue($data['value'] ?? null);
        $this->setType($data['type'] ?? null);
    }

    /**
     * {@inheritdoc}
     */
    public function extract(): array
    {
        return [
            'value' => $this->getValue(),
            'type' => $this->getType(),
        ];
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int $id
     *
     * @return Email
     */
    public function setId(int $id): Email
    {
        Assert::greaterThan($id, 0);

        $this->id = $id;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getValue(): ?string
    {
        return $this->value;
    }

    /**
     * @param string|null $value
     *
     * @return Email
     */
    public function setValue(?string $value): Email
    {
        $this->value = $value;

        return $this;
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
     *
     * @return Email
     */
    public function setType(?string $type): Email
    {
        $this->type = $type;

        return $this;
    }
}
