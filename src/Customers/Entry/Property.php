<?php

declare(strict_types=1);

namespace HelpScout\Api\Customers\Entry;

use HelpScout\Api\Entity\Extractable;
use HelpScout\Api\Entity\Hydratable;

class Property implements Extractable, Hydratable
{
    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $slug;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string|null
     */
    private $value;

    /**
     * @var array|null
     */
    private $source;

    /**
     * Operation for update.
     *
     * @var string (replace/remove)
     */
    private $operation;

    public function hydrate(array $data, array $embedded = [])
    {
        $this->setType($data['type'] ?? null);
        $this->setSlug($data['slug'] ?? null);
        $this->setName($data['name'] ?? null);
        $this->setValue($data['value'] ?? null);
        $this->setSource($data['source'] ?? null);
        $this->setOperation($data['operation'] ?? 'replace');
    }

    /**
     * {@inheritdoc}
     */
    public function extract(): array
    {
        return [
            'op' => $this->getOperation(),
            'value' => $this->getValue(),
            'path' => '/'.$this->getSlug(),
        ];
    }

    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string|null $type
     */
    public function setType($type): Property
    {
        $this->type = $type;

        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     */
    public function setName($name): Property
    {
        $this->name = $name;

        return $this;
    }

    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param string|null $value
     */
    public function setValue($value): Property
    {
        $this->value = $value;

        return $this;
    }

    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     */
    public function setSlug($slug): Property
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @return array|null $source
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * @param array $source
     */
    public function setSource($source): Property
    {
        $this->source = $source;

        return $this;
    }

    /**
     * @return string $operation
     */
    public function getOperation()
    {
        return $this->operation;
    }

    /**
     * @param string $operation
     */
    public function setOperation($operation): Property
    {
        $this->operation = $operation;

        return $this;
    }
}
