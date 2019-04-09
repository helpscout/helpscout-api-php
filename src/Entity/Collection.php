<?php

declare(strict_types=1);

namespace HelpScout\Api\Entity;

use ArrayObject;

class Collection extends ArrayObject
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
}
