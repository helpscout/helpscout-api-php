<?php

declare(strict_types=1);

namespace HelpScout\Api\Tests\Http;

use GuzzleHttp\Client;
use HelpScout\Api\Http\Auth\Auth;
use HelpScout\Api\Http\Authenticator;
use Mockery;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;

class AuthenticatorTest extends TestCase
{
    /** @var MockInterface */
    public $client;

    public function setUp()
    {
        $this->client = Mockery::mock(Client::class);
    }

    public function testSetsAndProvidesTokens()
    {
        $accessToken = '123512362';
        $refreshToken = 's5df5634s';

        $authenticator = new Authenticator($this->client);
        $authenticator->setAccessToken($accessToken);
        $authenticator->setRefreshToken($refreshToken);

        $this->assertSame($accessToken, $authenticator->accessToken());
        $this->assertSame($refreshToken, $authenticator->refreshToken());
    }

    public function testSetsAndProvidesCredentials()
    {
        $credentials = Mockery::mock(Auth::class);

        $authenticator = new Authenticator($this->client);
        $authenticator->setAuth($credentials);

        $this->assertSame($credentials, $authenticator->getAuthCredentials());
    }
}
