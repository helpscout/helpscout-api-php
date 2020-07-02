<?php

declare(strict_types=1);

namespace HelpScout\Api\Tests\Http;

use GuzzleHttp\Client;
use HelpScout\Api\Http\Auth\Auth;
use HelpScout\Api\Http\Auth\ClientCredentials;
use HelpScout\Api\Http\Auth\CodeCredentials;
use HelpScout\Api\Http\Auth\HandlesTokenRefreshes;
use HelpScout\Api\Http\Auth\NullCredentials;
use HelpScout\Api\Http\Auth\RefreshCredentials;
use HelpScout\Api\Http\Authenticator;
use Mockery;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;

class AuthenticatorTest extends TestCase
{
    /** @var MockInterface|Mockery\LegacyMockInterface */
    public $client;

    public function setUp(): void
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

    public function testTokensAreRefreshedWithoutCallbacks()
    {
        $authType = Mockery::mock(Auth::class, [
            'getPayload' => [],
        ]);

        $accessToken = uniqid();
        $expiresIn = rand(100, 200);
        $refreshToken = uniqid();
        $response = Mockery::mock(ResponseInterface::class, [
            'getBody' => json_encode([
                'access_token' => $accessToken,
                'expires_in' => $expiresIn,
                'refresh_token' => $refreshToken,
            ]),
        ]);
        $this->client->shouldReceive('request')
            ->andReturn($response);

        $authenticator = new Authenticator($this->client, $authType);

        $authenticator->fetchAccessAndRefreshToken();

        $this->assertEquals($accessToken, $authenticator->accessToken());
        $this->assertEquals($expiresIn, $authenticator->tokenExpiresIn());
        $this->assertEquals($refreshToken, $authenticator->refreshToken());
    }

    public function testTokensAreRefreshedWithClosureCallbacks()
    {
        $authType = Mockery::mock(Auth::class, [
            'getPayload' => [],
        ]);

        $accessToken = uniqid();
        $expiresIn = rand(100, 200);
        $refreshToken = uniqid();
        $response = Mockery::mock(ResponseInterface::class, [
            'getBody' => json_encode([
                'access_token' => $accessToken,
                'expires_in' => $expiresIn,
                'refresh_token' => $refreshToken,
            ]),
        ]);
        $this->client->shouldReceive('request')
            ->andReturn($response);

        $callbackExecuted = false;
        $authenticator = new Authenticator($this->client, $authType);
        $authenticator->callbackWhenTokenRefreshed(function (Authenticator $a) use (&$callbackExecuted, $accessToken, $expiresIn, $refreshToken) {
            $callbackExecuted = true;

            $this->assertEquals($accessToken, $a->accessToken());
            $this->assertEquals($expiresIn, $a->tokenExpiresIn());
            $this->assertEquals($refreshToken, $a->refreshToken());
        });

        $authenticator->fetchAccessAndRefreshToken();

        $this->assertTrue($callbackExecuted);
    }

    public function testTokensAreRefreshedWithObjectCallbacks()
    {
        $authType = Mockery::mock(Auth::class, [
            'getPayload' => [],
        ]);

        $accessToken = uniqid();
        $expiresIn = rand(100, 200);
        $refreshToken = uniqid();
        $response = Mockery::mock(ResponseInterface::class, [
            'getBody' => json_encode([
                'access_token' => $accessToken,
                'expires_in' => $expiresIn,
                'refresh_token' => $refreshToken,
            ]),
        ]);
        $this->client->shouldReceive('request')
            ->andReturn($response);

        $callback = new class() implements HandlesTokenRefreshes {
            public $executed = false;
            public $accessToken;
            public $expiresIn;
            public $refreshToken;

            public function whenTokenRefreshed(Authenticator $authenticator)
            {
                $this->executed = true;

                $this->accessToken = $authenticator->accessToken();
                $this->expiresIn = $authenticator->tokenExpiresIn();
                $this->refreshToken = $authenticator->refreshToken();
            }
        };

        $authenticator = new Authenticator($this->client, $authType);
        $authenticator->callbackWhenTokenRefreshed($callback);

        $authenticator->fetchAccessAndRefreshToken();

        $this->assertTrue($callback->executed);
        $this->assertEquals($accessToken, $callback->accessToken);
        $this->assertEquals($expiresIn, $callback->expiresIn);
        $this->assertEquals($refreshToken, $callback->refreshToken);
    }

    /**
     * @dataProvider autoRefreshAccessTokenProvider
     */
    public function testIdentifiesWhenToAutoRefreshToken(array $args)
    {
        // setting default values to keep phpstan happy
        $tokenType = null;
        $tokenCallback = function () {};
        $shouldAutoRefreshAccessToken = null;

        /*
         * @var string $tokenType
         * @var null|Closure $tokenCallback
         * @var bool $shouldAutoRefreshAccessToken
         */
        extract($args);

        $authType = Mockery::mock(Auth::class);
        $authType->shouldReceive('getType')->andReturn($tokenType);

        $authenticator = new Authenticator($this->client, $authType);
        $authenticator->callbackWhenTokenRefreshed($tokenCallback);

        $this->assertEquals(
            $shouldAutoRefreshAccessToken,
            $authenticator->shouldAutoRefreshAccessToken()
        );
    }

    public function autoRefreshAccessTokenProvider(): \Generator
    {
        $typesThatCanRefreshTokens = [
            ClientCredentials::TYPE,
            RefreshCredentials::TYPE,
            CodeCredentials::TYPE,
        ];
        foreach ($typesThatCanRefreshTokens as $type) {
            yield ['Should not refresh because there is no callback' => [
                'tokenType' => $type,
                'tokenCallback' => null,
                'shouldAutoRefreshAccessToken' => false,
            ]];
            yield ['Should refresh when callback and correct auth type' => [
                'tokenType' => $type,
                'tokenCallback' => function () {
                    return true;
                },
                'shouldAutoRefreshAccessToken' => true,
            ]];
            yield [[
                'tokenType' => $type,
                'tokenCallback' => function () {
                    return true;
                },
                'shouldAutoRefreshAccessToken' => true,
            ]];
        }

        $typesThatCannnotRefreshTokens = [
            NullCredentials::TYPE,
        ];
        foreach ($typesThatCannnotRefreshTokens as $type) {
            yield ['Should not refresh because there is no callback' => [
                'tokenType' => $type,
                'tokenCallback' => null,
                'shouldAutoRefreshAccessToken' => false,
            ]];
            yield ['Should not refresh when there is a callback' => [
                'tokenType' => $type,
                'tokenCallback' => function () {
                    return true;
                },
                'shouldAutoRefreshAccessToken' => false,
            ]];
        }
    }
}
