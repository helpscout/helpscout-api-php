<?php

declare(strict_types=1);

namespace HelpScout\Api\Http\Auth;

interface Auth
{
    /**
     * @return string
     */
    public function getType(): string;

    /**
     * @return array
     */
    public function getPayload(): array;
}
