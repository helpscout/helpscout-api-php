<?php

declare(strict_types=1);

namespace HelpScout\Api\Tests\Http\Handlers;

use HelpScout\Api\Exception\AuthenticationException;
use HelpScout\Api\Exception\ClientException;
use HelpScout\Api\Tests\ApiClientIntegrationTestCase;

class ClientHandlerTest extends ApiClientIntegrationTestCase
{
    public function testClientExceptionThrownWhenBadRequest()
    {
        $this->expectException(ClientException::class);

        $this->stubResponse($this->getResponse(400, json_encode([])));

        $this->client->customers()->get(1);
    }

    public function testClientExceptionThrownWhenAccessDenied()
    {
        $this->expectException(ClientException::class);

        $this->stubResponse($this->getResponse(403, json_encode([])));

        $this->client->customers()->get(1);
    }

    public function testClientExceptionThrownWhenNotFound()
    {
        $this->expectException(ClientException::class);

        $this->stubResponse($this->getResponse(404, json_encode([])));

        $this->client->customers()->get(1);
    }

    public function testClientExceptionNotThrownWhenNotAuthorized()
    {
        $this->expectException(AuthenticationException::class);

        $this->stubResponse($this->getResponse(401, json_encode([])));

        $this->client->customers()->get(1);
    }
}
