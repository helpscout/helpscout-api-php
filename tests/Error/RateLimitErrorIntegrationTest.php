<?php

declare(strict_types=1);

namespace HelpScout\Api\Tests\Error;

use HelpScout\Api\Exception\RateLimitExceededException;
use HelpScout\Api\Tests\ApiClientIntegrationTestCase;
use HelpScout\Api\Tests\Payloads\ErrorPayloads;

/**
 * @group integration
 */
class RateLimitErrorIntegrationTest extends ApiClientIntegrationTestCase
{
    public function testHandlesRateLimitExceededError()
    {
        $this->expectException(RateLimitExceededException::class);

        $this->stubResponse(
            $this->getResponse(429, ErrorPayloads::rateLimitExceededError())
        );

        $this->client->customers()->get(1);
    }
}
