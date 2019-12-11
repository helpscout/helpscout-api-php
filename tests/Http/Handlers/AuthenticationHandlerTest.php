<?php

declare(strict_types=1);

namespace HelpScout\Api\Tests\Http\Handlers;

use HelpScout\Api\Exception\AuthenticationException;
use HelpScout\Api\Tests\ApiClientIntegrationTestCase;

class AuthenticationHandlerTest extends ApiClientIntegrationTestCase
{
    public function testAuthorizationExceptionThrownWhenUnauthorizedStatusCode()
    {
        $this->expectException(AuthenticationException::class);

        $this->stubResponse($this->getResponse(401, json_encode([])));

        $this->client->customers()->get(1);
    }
}
