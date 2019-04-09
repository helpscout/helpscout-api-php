<?php

declare(strict_types=1);

namespace HelpScout\Api\Customers\Entry;

use HelpScout\Api\Assert\Assert;
use HelpScout\Api\Entity\Extractable;
use HelpScout\Api\Entity\Hydratable;

/**
 * "Chat" has been renamed to ChatHandle.  We're keeping all the logic in this class so as to avoid any backwards
 * incompatibilities.
 *
 * @deprecated
 */
class Chat implements Extractable, Hydratable
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
     * @return Chat
     */
    public function setId(int $id): Chat
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
     * @return Chat
     */
    public function setValue($value): Chat
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
     * @return Chat
     */
    public function setType($type): Chat
    {
        $this->type = $type;

        return $this;
    }
}
