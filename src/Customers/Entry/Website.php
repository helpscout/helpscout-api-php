<?php

declare(strict_types=1);

namespace HelpScout\Api\Customers\Entry;

use HelpScout\Api\Assert\Assert;
use HelpScout\Api\Entity\Extractable;
use HelpScout\Api\Entity\Hydratable;

class Website implements Extractable, Hydratable
{
    /**
     * @var int|null
     */
    private $id;

    /**
     * @var string|null
     */
    private $value;

    public function hydrate(array $data, array $embedded = [])
    {
        if (isset($data['id'])) {
            $this->setId($data['id']);
        }

        $this->setValue($data['value'] ?? null);
    }

    /**
     * {@inheritdoc}
     */
    public function extract(): array
    {
        return [
            'value' => $this->getValue(),
        ];
    }

    /**
     * @return int|null
     */
    public function getId()
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
     * @return string|null
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param string|null $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }
}
