<?php

declare(strict_types=1);

namespace HelpScout\Api\Tests\Authentication;

use GuzzleHttp\Client;
use HelpScout\Api\Http\Auth\ClientCredentials;
use HelpScout\Api\Http\Auth\LegacyCredentials;
use HelpScout\Api\Http\Auth\NullCredentials;
use HelpScout\Api\Http\Auth\RefreshCredentials;
use HelpScout\Api\Http\Authenticator;
use HelpScout\Api\Tests\ApiClientIntegrationTestCase;
use HelpScout\Api\Tests\Payloads\CustomerPayloads;
use Mockery;
use Mockery\MockInterface;

/**
 * @group integration
 */
class AuthenticationIntegrationTest extends ApiClientIntegrationTestCase
{
    /**
     * @var Client|MockInterface
     */
    protected $guzzle;

    public function setUp()
    {
        parent::setUp();

        /* @var Client $client */
        $this->guzzle = Mockery::mock(Client::class);
    }

    public function testAuthenticatesRequests()
    {
        $this->stubResponse($this->getResponse(200, CustomerPayloads::getCustomer(1)));

        $this->client->customers()->get(1);

        $requests = $this->history;
        $this->assertCount(1, $requests);
        $this->assertTrue($requests[0]['request']->hasHeader('Authorization'));
        $this->assertSame(['Bearer abc123'], $requests[0]['request']->getHeader('Authorization'));
    }

    public function testGetAuthCredentials()
    {
        $auth = $this->authenticator->getAuthCredentials();
        $this->assertInstanceOf(
            NullCredentials::class,
            $auth
        );

        $this->assertSame(NullCredentials::TYPE, $auth->getType());
        $this->assertEmpty($auth->getPayload());
    }

    public function testClientCredentialsSetsAuth()
    {
        $appId = '123abc';
        $appSecret = 'fdafda';
        $expectedPayload = [
            'grant_type' => 'client_credentials',
            'client_id' => $appId,
            'client_secret' => $appSecret,
        ];

        $this->authenticator->useClientCredentials($appId, $appSecret);
        $auth = $this->authenticator->getAuthCredentials();
        $this->assertInstanceOf(ClientCredentials::class, $auth);
        $this->assertSame($expectedPayload, $auth->getPayload());
    }

    public function testLegacyCredentialsSetsAuth()
    {
        $clientId = '123abc';
        $apiKey = 'fdafda';
        $expectedPayload = [
            'clientId' => $clientId,
            'apiKey' => $apiKey,
        ];

        $this->authenticator->useLegacyToken($clientId, $apiKey);
        $auth = $this->authenticator->getAuthCredentials();
        $this->assertSame(LegacyCredentials::TYPE, $auth->getType());
        $this->assertInstanceOf(LegacyCredentials::class, $auth);
        $this->assertSame($expectedPayload, $auth->getPayload());
    }

    public function testUseRefreshTokenCredentials()
    {
        $appId = '123abc';
        $appSecret = 'fdafda';
        $refreshToken = 'asdfasdf';
        $expectedPayload = [
            'grant_type' => 'refresh_token',
            'refresh_token' => $refreshToken,
            'client_id' => $appId,
            'client_secret' => $appSecret,
        ];

        $this->authenticator->useRefreshToken($appId, $appSecret, $refreshToken);
        $auth = $this->authenticator->getAuthCredentials();
        $this->assertInstanceOf(RefreshCredentials::class, $auth);
        $this->assertSame($expectedPayload, $auth->getPayload());
    }

    public function testAuthenticatorFetchesTokensWithLegacyCredentials()
    {
        $clientId = '123abc';
        $apiKey = 'fdafda';
        $auth = new LegacyCredentials($clientId, $apiKey);
        $expectedPayload = [
            'clientId' => $clientId,
            'apiKey' => $apiKey,
        ];

        $expectedOptions = [
            'headers' => [
                'Content-Type' => 'application/json;charset=UTF-8',
            ],
            'json' => $expectedPayload,
        ];

        $tokenResponse = [
            'accessToken' => 'fdsafdas',
            'refreshToken' => 'asdfasdf',
            'expiresIn' => 7200,
        ];
        $expectedResponse = $this->getResponse(200, json_encode($tokenResponse));

        $this->guzzle->shouldReceive('request')
            ->with('POST', Authenticator::TRANSITION_URL, $expectedOptions)
            ->andReturn($expectedResponse);

        $expectedResult = [
            'Authorization' => 'Bearer fdsafdas',
        ];

        $authenticator = new Authenticator(new Client(), $auth);
        $authenticator->setClient($this->guzzle);
        $result = $authenticator->getAuthHeader();
        $this->assertSame($expectedResult, $result);
    }

    public function testAuthenticatorFetchesTokensWithClientCredentials()
    {
        $appId = '123abc';
        $appSecret = 'fdafda';
        $auth = new ClientCredentials($appId, $appSecret);
        $expectedPayload = [
            'grant_type' => ClientCredentials::TYPE,
            'client_id' => $appId,
            'client_secret' => $appSecret,
        ];

        $expectedOptions = [
            'headers' => [
                'Content-Type' => 'application/json;charset=UTF-8',
            ],
            'json' => $expectedPayload,
        ];

        $tokenResponse = [
            'access_token' => 'fdsafdas',
            'refresh_token' => 'asdfasdf',
            'expires_in' => 7200,
        ];
        $expectedResponse = $this->getResponse(200, json_encode($tokenResponse));

        $this->guzzle->shouldReceive('request')
            ->with('POST', Authenticator::TOKEN_URL, $expectedOptions)
            ->andReturn($expectedResponse);

        $expectedResult = [
            'Authorization' => 'Bearer fdsafdas',
        ];

        $authenticator = new Authenticator(new Client(), $auth);

        $authenticator->setClient($this->guzzle);
        $result = $authenticator->getAuthHeader();
        $this->assertSame($expectedResult, $result);
    }

    public function testAuthenticatorFetchesTokensWithRefreshCredentials()
    {
        $appId = '123abc';
        $appSecret = 'fdafda';
        $refreshToken = 'poiuyt';
        $auth = new RefreshCredentials($appId, $appSecret, $refreshToken);
        $expectedPayload = [
            'grant_type' => RefreshCredentials::TYPE,
            'refresh_token' => $refreshToken,
            'client_id' => $appId,
            'client_secret' => $appSecret,
        ];

        $expectedOptions = [
            'headers' => [
                'Content-Type' => 'application/json;charset=UTF-8',
            ],
            'json' => $expectedPayload,
        ];

        $tokenResponse = [
            'access_token' => 'fdsafdas',
            'refresh_token' => 'asdfasdf',
            'expires_in' => 7200,
        ];
        $expectedResponse = $this->getResponse(200, json_encode($tokenResponse));

        $this->guzzle->shouldReceive('request')
            ->once()
            ->with('POST', Authenticator::TOKEN_URL, $expectedOptions)
            ->andReturn($expectedResponse);

        $expectedResult = [
            'Authorization' => 'Bearer fdsafdas',
        ];

        $authenticator = new Authenticator(new Client(), $auth);

        /* @var Client $client */
        $authenticator->setClient($this->guzzle);
        $result = $authenticator->getAuthHeader();
        $this->assertSame($expectedResult, $result);

        $expectedTokens = [
            'refresh_token' => 'asdfasdf',
            'token_type' => 'Bearer',
            'access_token' => 'fdsafdas',
            'expires_in' => 7200,
        ];
        $this->assertSame($expectedTokens, $authenticator->getTokens());
    }

    public function testFetchTokensWithNullCredentialsThrowsException()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Cannot fetch tokens without app credentials');

        $authenticator = new Authenticator($this->guzzle);
        $authenticator->getAuthHeader();
    }
}
