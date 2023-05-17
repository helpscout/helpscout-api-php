<?php

declare(strict_types=1);

namespace HelpScout\Api\Tests\Authentication;

use GuzzleHttp\Client;
use HelpScout\Api\Http\Auth\ClientCredentials;
use HelpScout\Api\Http\Auth\NullCredentials;
use HelpScout\Api\Http\Auth\RefreshCredentials;
use HelpScout\Api\Http\Authenticator;
use HelpScout\Api\Tests\ApiClientIntegrationTestCase;
use HelpScout\Api\Tests\Payloads\CustomerPayloads;
use Mockery;

/**
 * @group integration
 */
class AuthenticationIntegrationTest extends ApiClientIntegrationTestCase
{
    /**
     * @var string
     */
    protected $accessToken = null;

    public function testAuthenticatesRequests()
    {
        $this->accessToken = 'abc123';
        $this->setUp();

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

        $tokenResponse = [
            'access_token' => 'fdsafdas',
            'refresh_token' => 'asdfasdf',
            'expires_in' => 7200,
        ];
        $expectedResponse = $this->getResponse(200, json_encode($tokenResponse));

        $this->stubResponse($expectedResponse);

        $expectedResult = [
            'Authorization' => 'Bearer fdsafdas',
        ];

        $this->authenticator->setAuth($auth);

        $result = $this->authenticator->getAuthHeader();
        $this->assertSame($expectedResult, $result);

        $requests = $this->history;
        $this->assertSame('POST', $requests[0]['request']->getMethod());
        $this->assertSame(Authenticator::TOKEN_URL, (string) $requests[0]['request']->getUri());
        $this->assertSame(['application/json;charset=UTF-8'], $requests[0]['request']->getHeader('Content-Type'));
        $this->assertSame($expectedPayload, json_decode((string) $requests[0]['request']->getBody(), true));
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

        $tokenResponse = [
            'access_token' => 'fdsafdas',
            'refresh_token' => 'asdfasdf',
            'expires_in' => 7200,
        ];
        $expectedResponse = $this->getResponse(200, json_encode($tokenResponse));

        $this->stubResponse($expectedResponse);

        $expectedResult = [
            'Authorization' => 'Bearer fdsafdas',
        ];

        $this->authenticator->setAuth($auth);

        $result = $this->authenticator->getAuthHeader();
        $this->assertSame($expectedResult, $result);

        $expectedTokens = [
            'refresh_token' => 'asdfasdf',
            'token_type' => 'Bearer',
            'access_token' => 'fdsafdas',
            'expires_in' => 7200,
        ];
        $this->assertSame($expectedTokens, $this->authenticator->getTokens());

        $requests = $this->history;
        $this->assertSame('POST', $requests[0]['request']->getMethod());
        $this->assertSame(Authenticator::TOKEN_URL, (string) $requests[0]['request']->getUri());
        $this->assertSame(['application/json;charset=UTF-8'], $requests[0]['request']->getHeader('Content-Type'));
        $this->assertSame($expectedPayload, json_decode((string) $requests[0]['request']->getBody(), true));
    }

    public function testFetchTokensWithNullCredentialsThrowsException()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Cannot fetch tokens without app credentials');

        $this->authenticator->getAuthHeader();
    }
}
