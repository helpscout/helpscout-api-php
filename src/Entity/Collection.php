<?php

declare(strict_types=1);

namespace HelpScout\Api\Entity;

use ArrayObject;

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
        $a_result = array();
        foreach ($this as $o_entry) {
            $a_result[] = $o_entry->extract();
        }
        return $a_result;
    }
}

