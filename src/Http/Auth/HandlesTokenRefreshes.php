<?php

declare(strict_types=1);

namespace HelpScout\Api\Http\Auth;

use HelpScout\Api\Http\Authenticator;

interface HandlesTokenRefreshes
{
    public function whenTokenRefreshed(Authenticator $authenticator);
}
