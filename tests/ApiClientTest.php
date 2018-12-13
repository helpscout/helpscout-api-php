<?php

declare(strict_types=1);

namespace HelpScout\Api\Tests;

use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use HelpScout\Api\ApiClient;
use HelpScout\Api\Http\Authenticator;
use HelpScout\Api\Http\History;
use HelpScout\Api\Http\RestClient;
use Mockery;
use PHPUnit\Framework\TestCase;

class ApiClientTest extends TestCase
{
    /**
     * @var History
     */
    private $history;

    /**
     * @var ApiClient
     */
    private $client;

    public function setUp()
    {
        /** @var RestClient $restClient */
        $restClient = Mockery::mock(RestClient::class);
        /** @var Authenticator $authenticator */
        $authenticator = Mockery::mock(Authenticator::class);

        $this->history = new History();
        $this->client = new ApiClient($restClient, $authenticator, $this->history);
    }

    public function testGetLastResponse()
    {
        $response = new Response();
        $this->history->addSuccess(new Request('GET', '/'), $response);

        $this->assertSame($response, $this->client->getLastResponse());
    }

    public function testGetLastResponseWithoutAnyHistory()
    {
        $this->assertNull($this->client->getLastResponse());
    }

    public function testRunReportWithInvalidReport()
    {
        $report = '\Does\Not\Exist';
        $this->expectException(\InvalidArgumentException::class);

        $this->client->runReport($report, []);
    }

    public function testRunReport()
    {
        $report = '\HelpScout\Api\Reports\User\Overall';
        $params = [
            'start' => new \DateTime('now'),
        ];
        $response = ['this is the response'];

        /** @var RestClient $restClient */
        $restClient = Mockery::mock(RestClient::class)
            ->shouldReceive('getReport')
            ->andReturn($response)
            ->getMock();

        /** @var Authenticator $authenticator */
        $authenticator = Mockery::mock(Authenticator::class);

        $history = new History();
        $client = new ApiClient($restClient, $authenticator, $history);

        $this->assertSame($response, $client->runReport($report, $params));
    }

    public function testGetTokensReturnsEmptyTokens()
    {
        /** @var RestClient $restClient */
        $restClient = Mockery::mock(RestClient::class);

        /** @var Authenticator $authenticator */
        $authenticator = Mockery::mock(Authenticator::class);

        $history = new History();
        $client = new ApiClient($restClient, $authenticator, $history);

        $this->assertEmpty($client->getTokens());
    }

    public function testUseClientCredentials()
    {
        $appId = 'asdfasdf';
        $appSecret = 'asdfasdfasdf';

        $accessToken = '123abc';
        $refreshToken = 'abc123';

        $tokens = [
            'access_token' => $accessToken,
            'refresh_token' => $refreshToken,
        ];

        $secondTokens = [
            'access_token' => 'aaabbb',
            'refresh_token' => 'cccddd',
        ];

        /** @var RestClient $restClient */
        $restClient = Mockery::mock(RestClient::class);
        $restClient->shouldReceive('fetchAccessAndRefreshToken')
            ->once()
            ->with($appId, $appSecret)
            ->andReturn($tokens);
        $restClient->shouldReceive('refreshTokens')
            ->once()
            ->with($appId, $appSecret, $refreshToken)
            ->andReturn($secondTokens);

        /** @var Authenticator $authenticator */
        $authenticator = Mockery::mock(Authenticator::class);
        $authenticator->shouldReceive('setAccessToken')
            ->once()
            ->with($accessToken);
        $authenticator->shouldReceive('setAccessToken')
            ->once()
            ->with('aaabbb');

        $history = new History();
        $client = new ApiClient($restClient, $authenticator, $history);

        $client->useClientCredentials($appId, $appSecret);
        $this->assertSame($tokens, $client->getTokens());

        $client->refreshAccessToken($appId, $appSecret);
        $this->assertSame($secondTokens, $client->getTokens());
    }

    public function testRefreshTokenWithNoRefreshToken()
    {
        $appId = 'asdfasdf';
        $appSecret = 'asdfasdfasdf';

        $accessToken = '123abc';
        $refreshToken = 'abc123';

        $tokens = [
            'access_token' => $accessToken,
            'refresh_token' => $refreshToken,
        ];

        /** @var RestClient $restClient */
        $restClient = Mockery::mock(RestClient::class);
        $restClient->shouldReceive('fetchAccessAndRefreshToken')
            ->once()
            ->with($appId, $appSecret)
            ->andReturn($tokens);

        /** @var Authenticator $authenticator */
        $authenticator = Mockery::mock(Authenticator::class);
        $authenticator->shouldReceive('setAccessToken')
            ->once()
            ->with($accessToken);

        $history = new History();
        $client = new ApiClient($restClient, $authenticator, $history);

        $client->refreshAccessToken($appId, $appSecret);
        $this->assertSame($tokens, $client->getTokens());
    }

    public function testConvertLegacyToken()
    {
        $clientId = 'asdfasdf';
        $apiKey = 'asdfasdfasdf';

        $accessToken = '123abc';
        $refreshToken = 'abc123';

        $tokens = [
            'access_token' => $accessToken,
            'refresh_token' => $refreshToken,
        ];

        /** @var RestClient $restClient */
        $restClient = Mockery::mock(RestClient::class);
        $restClient->shouldReceive('convertLegacyToken')
            ->once()
            ->with($clientId, $apiKey)
            ->andReturn($tokens);

        /** @var Authenticator $authenticator */
        $authenticator = Mockery::mock(Authenticator::class);
        $authenticator->shouldReceive('setAccessToken')
            ->once()
            ->with($accessToken);

        $history = new History();
        $client = new ApiClient($restClient, $authenticator, $history);

        $client->useLegacyToken($clientId, $apiKey);
        $this->assertSame($tokens, $client->getTokens());
    }
}
