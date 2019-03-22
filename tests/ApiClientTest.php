<?php

declare(strict_types=1);

namespace HelpScout\Api\Tests;

use GuzzleHttp\Client;
use HelpScout\Api\ApiClient;
use HelpScout\Api\ApiClientFactory;
use HelpScout\Api\Http\Auth\CodeCredentials;
use HelpScout\Api\Http\Authenticator;
use HelpScout\Api\Http\RestClient;
use HelpScout\Api\Webhooks\WebhooksEndpoint;
use HelpScout\Api\Workflows\WorkflowsEndpoint;
use Mockery;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;

class ApiClientTest extends TestCase
{
    /**
     * @var MockInterface|Authenticator
     */
    private $authenticator;

    /**
     * @var RestClient
     */
    private $restClient;

    /**
     * @var ApiClient
     */
    private $client;

    /**
     * @var MockInterface|Client
     */
    private $guzzle;

    public function setUp()
    {
        $this->authenticator = Mockery::mock(Authenticator::class);
        $this->guzzle = Mockery::mock(Client::class);

        $this->restClient = new RestClient($this->guzzle, $this->authenticator);

        $this->client = new ApiClient($this->restClient);
    }

    public function testCreateClient()
    {
        $this->assertInstanceOf(
            ApiClient::class,
            ApiClientFactory::createClient()
        );
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

        $client = new ApiClient($restClient);

        $this->assertSame($response, $client->runReport($report, $params));
    }

    public function testSecondFetchUsesContainer()
    {
        $endpoint = $this->client->workflows();
        $this->assertSame(
            $endpoint,
            $this->client->workflows()
        );
    }

    public function testMockReturnsProperMock()
    {
        $mockedWorkflows = $this->client->mock('workflows');

        $this->assertInstanceOf(WorkflowsEndpoint::class, $mockedWorkflows);
        $this->assertInstanceOf(MockInterface::class, $mockedWorkflows);

        $this->assertSame(
            $mockedWorkflows,
            $this->client->workflows()
        );

        $this->client->mock('webhooks');
        $this->client->clearMock('workflows');

        $workflows = $this->client->workflows();
        $this->assertInstanceOf(WorkflowsEndpoint::class, $workflows);
        $this->assertFalse($workflows instanceof MockInterface);

        $webhookMock = $this->client->webhooks();
        $this->assertInstanceOf(MockInterface::class, $webhookMock);

        $this->client->clearContainer();

        $webhooks = $this->client->webhooks();
        $this->assertInstanceOf(WebhooksEndpoint::class, $webhooks);
        $this->assertFalse($webhooks instanceof MockInterface);
    }

    public function testGetAuthenticator()
    {
        $this->assertSame(
            $this->authenticator,
            $this->client->getAuthenticator()
        );
    }

    public function testSetAccessToken()
    {
        $this->authenticator->shouldReceive('setAccessToken')
            ->once()
            ->with('123abc');
        $result = $this->client->setAccessToken('123abc');
        $this->assertSame(
            $result,
            $this->client
        );
    }

    public function testGetTokens()
    {
        $tokens = ['tokens'];
        $this->authenticator->shouldReceive('getTokens')
            ->once()
            ->andReturn($tokens);
        $this->assertSame(
            $tokens,
            $this->client->getTokens()
        );
    }

    public function testUseClientCredentials()
    {
        $appId = 'abc123';
        $appSecret = '321cba';

        $this->authenticator->shouldReceive('useClientCredentials')
            ->once()
            ->with($appId, $appSecret);
        $result = $this->client->useClientCredentials($appId, $appSecret);
        $this->assertSame(
            $result,
            $this->client
        );
    }

    public function testUseLegacyCredentials()
    {
        $clientId = 'abc123';
        $apiKey = '321cba';

        $this->authenticator->shouldReceive('useLegacyToken')
            ->once()
            ->with($clientId, $apiKey);
        $result = $this->client->useLegacyToken($clientId, $apiKey);
        $this->assertSame(
            $result,
            $this->client
        );
    }

    public function testUseRefreshToken()
    {
        $appId = 'abc123';
        $appSecret = '321cba';
        $refreshToken = '987fdsa';

        $this->authenticator->shouldReceive('useRefreshToken')
            ->once()
            ->with($appId, $appSecret, $refreshToken);
        $result = $this->client->useRefreshToken($appId, $appSecret, $refreshToken);
        $this->assertSame(
            $result,
            $this->client
        );
    }

    public function testSwapsAuthorizationCodeForAccessAndRefreshTokens()
    {
        $appId = 'abc123';
        $appSecret = '321cba';
        $authorizationCode = '987fdsa';

        $refreshToken = '987fdsa';

        $this->authenticator->shouldReceive('setAuth')
            ->with(Mockery::on(function ($argument) use ($appId, $appSecret, $authorizationCode) {
                if ($argument instanceof CodeCredentials === false) {
                    return false;
                }

                return $argument->getAppId() === $appId &&
                    $argument->getAppSecret() === $appSecret &&
                    $argument->getCode() === $authorizationCode;
            }))
            ->once();
        $this->authenticator->shouldReceive('fetchAccessAndRefreshToken')
            ->once();
        $this->authenticator->shouldReceive('refreshToken')
            ->andReturn($refreshToken);
        $this->authenticator->shouldReceive('useRefreshToken')
            ->with($appId, $appSecret, $refreshToken)
            ->once();

        $result = $this->client->swapAuthorizationCodeForReusableTokens($appId, $appSecret, $authorizationCode);
        $this->assertSame(
            $result,
            $this->client
        );
    }
}
