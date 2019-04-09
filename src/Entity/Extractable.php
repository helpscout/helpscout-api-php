<?php

declare(strict_types=1);

namespace HelpScout\Api\Entity;

interface Extractable
{
    public const DATETIME_FORMAT = 'Y-m-d\TH:i:s\Z';

    public function extract(): array;
}
