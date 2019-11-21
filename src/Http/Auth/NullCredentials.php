<?php

declare(strict_types=1);

namespace HelpScout\Api\Http\Auth;

class NullCredentials implements Auth
{
    public const TYPE = 'null_credentials';

    public function getType(): string
    {
        return self::TYPE;
    }

    public function getPayload(): array
    {
        return [];
    }
}
