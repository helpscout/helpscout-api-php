<?php

declare(strict_types=1);

namespace HelpScout\Api\Support;

use DateTime;

trait HydratesData
{
    private function transformDateTime(?string $dateTime): ?DateTime
    {
        if ($dateTime === null) {
            return null;
        }

        return new DateTime($dateTime);
    }
}
