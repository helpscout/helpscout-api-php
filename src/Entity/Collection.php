<?php

declare(strict_types=1);

namespace HelpScout\Api\Entity;

use ArrayObject;
use HelpScout\Api\Exception\RuntimeException;

class Collection extends ArrayObject implements Extractable
{
    public function __construct(array $items = [])
    {
        parent::__construct($items);
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return $this->getArrayCopy();
    }

    /**
     * {@inheritdoc}
     */
    public function extract(): array
    {
        $extracted = [];

        foreach ($this as $entry) {
            if ($entry instanceof Extractable === false) {
                throw new RuntimeException('Entity is not extractable');
            }

            $extracted[] = $entry->extract();
        }

        return $extracted;
    }
}
