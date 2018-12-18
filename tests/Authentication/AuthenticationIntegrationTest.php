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
        $this->stubResponse($this->getResponse(200, CustomerPayloads::getCustomer(1)));

        $this->client->customers()->get(1);

        $requests = $this->history;
        $this->assertCount(1, $requests);
        $this->assertTrue($requests[0]['request']->hasHeader('Authorization'));
        $this->assertSame(['Bearer abc123'], $requests[0]['request']->getHeader('Authorization'));
    }
}
