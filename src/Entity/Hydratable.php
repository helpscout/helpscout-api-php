<?php

declare(strict_types=1);

namespace HelpScout\Api\Entity;

interface Hydratable
{
    public function hydrate(array $data, array $embedded = []);
}
