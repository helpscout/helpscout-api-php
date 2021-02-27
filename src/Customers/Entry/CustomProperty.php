<?php

declare(strict_types=1);

namespace HelpScout\Api\Customers\Entry;

use HelpScout\Api\Entity\Extractable;
use HelpScout\Api\Entity\Hydratable;

class CustomProperty implements Extractable, Hydratable
{
    /**
     * @var string
     */
    private $slug;

    /**
     * @var string|null
     */
    private $value;

    public function hydrate(array $data, array $embedded = [])
    {
        $this->setSlug($data['slug'] ?? null);
        $this->setValue($data['text'] ?? null);
    }

    /**
     * {@inheritdoc}
     */
    public function extract(): array
    {
        return [
            'slug' => $this->getSlug(),
            'value' => $this->getValue(),
        ];
    }

    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param string|null $value
     */
    public function setValue($value): CustomProperty
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
    public function setSlug($slug): CustomProperty
    {
        $this->slug = $slug;

        return $this;
    }
}
