<?php

declare(strict_types=1);

namespace HelpScout\Api\Http;

use HelpScout\Api\Http\Plugin\AuthenticationPlugin;
use Http\Message\Authentication\Bearer;

class Authenticator
{
    /**
     * @var AuthenticationPlugin
     */
    private $authenticationPlugin;

    /**
     * @param AuthenticationPlugin $authenticationPlugin
     */
    public function __construct(AuthenticationPlugin $authenticationPlugin)
    {
        $this->authenticationPlugin = $authenticationPlugin;
    }

    /**
     * @param string $accessToken
     */
    public function setAccessToken(string $accessToken)
    {
        $this->authenticationPlugin->setAuthentication(new Bearer($accessToken));
    }
}
