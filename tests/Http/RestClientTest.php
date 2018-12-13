<?php

declare(strict_types=1);

namespace HelpScout\Api\Tests\Http;

use GuzzleHttp\Psr7\Response;
use HelpScout\Api\Http\RestClient;
use HelpScout\Api\Reports\Docs\Overall;
use HelpScout\Api\Reports\ParameterBag;
use Http\Client\Common\HttpMethodsClient;
use PHPUnit\Framework\TestCase;

class RestClientTest extends TestCase
{
    public $methodsClient;

    public function setUp()
    {
        $this->methodsClient = \Mockery::mock(HttpMethodsClient::class);
    }

    public function testFetchAccessAndRefreshToken()
    {
        $appId = 'asdlkjhljkds8';
        $appSecret = 'dlhkflhsdf89';

        $url = 'https://api.helpscout.net/v2/oauth2/token';
        $headers = [
            'Content-Type' => 'application/json;charset=UTF-8',
            'X-Token-Request' => true,
        ];
        $payload = [
            'grant_type' => 'client_credentials',
            'client_id' => $appId,
            'client_secret' => $appSecret,
        ];

        $responseData = [
            'access_token' => 'aaabbb',
            'refresh_token' => 'cccddd',
        ];

        $response = new Response(200, [], json_encode($responseData));

        $this->methodsClient->shouldReceive('post')
            ->with($url, $headers, json_encode($payload))
            ->andReturn($response);

        $restClient = new RestClient($this->methodsClient);
        $result = $restClient->fetchAccessAndRefreshToken($appId, $appSecret);
        $this->assertSame($responseData, $result);
    }

    public function testRefreshTokens()
    {
        $appId = 'asdlkjhljkds8';
        $appSecret = 'dlhkflhsdf89';
        $oldRefreshToken = 'shafh9vhu89ad';

        $url = 'https://api.helpscout.net/v2/oauth2/token';
        $headers = [
            'Content-Type' => 'application/json;charset=UTF-8',
            'X-Token-Request' => true,
        ];
        $payload = [
            'grant_type' => 'refresh_token',
            'refresh_token' => $oldRefreshToken,
            'client_id' => $appId,
            'client_secret' => $appSecret,
        ];

        $responseData = [
            'access_token' => 'aaabbb',
            'refresh_token' => 'cccddd',
        ];

        $response = new Response(200, [], json_encode($responseData));

        $this->methodsClient->shouldReceive('post')
            ->with($url, $headers, json_encode($payload))
            ->andReturn($response);

        $restClient = new RestClient($this->methodsClient);
        $result = $restClient->refreshTokens($appId, $appSecret, $oldRefreshToken);
        $this->assertSame($responseData, $result);
    }

    public function testConvertLegacyTokens()
    {
        $clientId = 'asdlkjhljkds8';
        $apiKey = 'dlhkflhsdf89';

        $url = 'https://transition.helpscout.net';
        $headers = [
            'Content-Type' => 'application/json;charset=UTF-8',
            'X-Token-Request' => true,
        ];
        $payload = [
            'clientId' => $clientId,
            'apiKey' => $apiKey,
        ];

        $responseData = [
            'accessToken' => 'aaabbb',
            'refreshToken' => 'cccddd',
            'expiresIn' => 123,
            'token_type' => 'bearer',
        ];

        $expected = [
            'access_token' => 'aaabbb',
            'refresh_token' => 'cccddd',
            'expires_in' => 123,
            'token_type' => 'bearer',
        ];

        $response = new Response(200, [], json_encode($responseData));

        $this->methodsClient->shouldReceive('post')
            ->with($url, $headers, json_encode($payload))
            ->andReturn($response);

        $restClient = new RestClient($this->methodsClient);
        $result = $restClient->convertLegacyToken($clientId, $apiKey);
        $this->assertSame($expected, $result);
    }

    public function testRunReport()
    {
        $params = new ParameterBag([]);
        $report = new Overall($params);

        $responseData = [
            'current' => 'aaabbb',
            'previous' => 'cccddd',
        ];

        $response = new Response(200, [], json_encode($responseData));

        $this->methodsClient->shouldReceive('get')
            ->andReturn($response);

        $restClient = new RestClient($this->methodsClient);
        $result = $restClient->getReport($report);
        $this->assertSame($responseData, $result);
    }
}
