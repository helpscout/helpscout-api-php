<?php

declare(strict_types=1);

namespace HelpScout\Api\Tests\Http\Handlers;

use GuzzleHttp\Exception\RequestException;
use HelpScout\Api\Tests\ApiClientIntegrationTestCase;

class ClientHandlerTest extends ApiClientIntegrationTestCase
{
    public function testRequestExceptionThrownWhenBadRequest()
    {
        $this->expectException(RequestException::class);

        $this->stubResponse($this->getResponse(400, json_encode([])));

        $this->client->customers()->get(1);
    }

    public function testRequestExceptionThrownWhenAccessDenied()
    {
        $this->expectException(RequestException::class);

        $this->stubResponse($this->getResponse(403, json_encode([])));

        $this->client->customers()->get(1);
    }

    public function testRequestExceptionThrownWhenNotFound()
    {
        $this->expectException(RequestException::class);

        $this->stubResponse($this->getResponse(404, json_encode([])));

        $this->client->customers()->get(1);
    }
}
