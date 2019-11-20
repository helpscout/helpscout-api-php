<?php

declare(strict_types=1);

namespace HelpScout\Api\Http\Auth;

interface Auth
{
    public function getType(): string;

    public function getPayload(): array;
}
