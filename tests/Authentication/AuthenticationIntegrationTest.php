<?php

declare(strict_types=1);

namespace HelpScout\Api\Tests\Authentication;

use HelpScout\Api\Tests\ApiClientIntegrationTestCase;
use HelpScout\Api\Tests\Payloads\CustomerPayloads;

/**
 * @group integration
 */
class AuthenticationIntegrationTest extends ApiClientIntegrationTestCase
{
    public function testAuthenticatesRequests()
    {
        $this->stubResponse(200, CustomerPayloads::getCustomer(1));

        $this->client->customers()->get(1);

        $requests = $this->mockHttpClient->getRequests();
        $this->assertCount(1, $requests);
        $this->assertTrue($requests[0]->hasHeader('Authorization'));
        $this->assertSame(['Bearer secret'], $requests[0]->getHeader('Authorization'));
    }
}
