<?php

declare(strict_types=1);

namespace HelpScout\Api\Tests\Http\Hal\Entity;

use HelpScout\Api\Entity\Extractable;
use HelpScout\Api\Entity\Hydratable;

class StubEntity implements Extractable, Hydratable
{
    /**
     * @var array
     */
    public $data = [];

    public function hydrate(array $data, array $embedded = [])
    {
        $this->data = $data;
    }

    /**
     * {@inheritdoc}
     */
    public function extract(): array
    {
        return $this->data;
    }
}
