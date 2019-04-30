<?php

declare(strict_types=1);

namespace HelpScout\Api\Entity;

interface Extractable
{
    public function extract(): array;
}
