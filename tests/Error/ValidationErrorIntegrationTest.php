<?php

declare(strict_types=1);

namespace HelpScout\Api\Tests\Error;

use GuzzleHttp\Exception\RequestException;
use HelpScout\Api\Customers\Customer;
use HelpScout\Api\Exception\ValidationErrorException;
use HelpScout\Api\Tests\ApiClientIntegrationTestCase;
use HelpScout\Api\Tests\Payloads\ErrorPayloads;

/**
 * @group integration
 */
class ValidationErrorIntegrationTest extends ApiClientIntegrationTestCase
{
    public function testHandlesValidationError()
    {
        $this->expectException(ValidationErrorException::class);

        $this->stubResponse(
            $this->getResponse(
                400,
                ErrorPayloads::validationErrors(),
                ['Content-Type' => ['application/vnd.error+json']]
            )
        );

        try {
            $this->client->customers()->create(new Customer());
        } catch (ValidationErrorException $exception) {
            $this->assertSame('Validation error', $exception->getError()->getMessage());

            throw $exception;
        }
    }

    public function testDoesNotHandleValidationErrorWithoutVndErrorContentType()
    {
        $this->expectException(RequestException::class);

        $this->stubResponse(
            $this->getResponse(
                400,
                ErrorPayloads::validationErrors(),
                ['Content-Type' => 'application/json;charset=UTF-8']
            )
        );

        $this->client->customers()->create(new Customer());
    }

    public function testDoesNotHandleValidationErrorWithoutContentTypeHeader()
    {
        $this->expectException(RequestException::class);

        $this->stubResponse(
            $this->getResponse(
                400,
                ErrorPayloads::validationErrors()
            )
        );

        $this->client->customers()->create(new Customer());
    }
}
