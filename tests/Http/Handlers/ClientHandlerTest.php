<?php

declare(strict_types=1);

namespace HelpScout\Api\Tests\Http\Handlers;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use HelpScout\Api\ApiClient;
use HelpScout\Api\Customers\Customer;
use HelpScout\Api\Exception\ClientException;
use HelpScout\Api\Http\Authenticator;
use HelpScout\Api\Http\RestClient;
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
        $this->mockHandler = new MockHandler();
        $handler = HandlerStack::create($this->mockHandler);

        $client = new Client(['handler' => $handler]);
        $this->authenticator = new Authenticator($client);
        $this->authenticator->setAccessToken('abc123');

        $this->client = new ApiClient(
            new RestClient($client, $this->authenticator)
        );

        $this->stubResponse($this->getResponse(401, json_encode([])));

        $this->assertInstanceOf(
            Customer::class,
            $this->client->customers()->get(1)
        );
    }
}
