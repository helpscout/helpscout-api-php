<?php

declare(strict_types=1);

namespace HelpScout\Api\Tests\Http;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use HelpScout\Api\Http\Auth\ClientCredentials;
use HelpScout\Api\Http\Auth\LegacyCredentials;
use HelpScout\Api\Http\Auth\NullCredentials;
use HelpScout\Api\Http\Auth\RefreshCredentials;
use HelpScout\Api\Http\Authenticator;
use HelpScout\Api\Http\RestClient;
use HelpScout\Api\Http\RestClientBuilder;
use HelpScout\Api\Reports\Docs\Overall;
use HelpScout\Api\Reports\ParameterBag;
use PHPUnit\Framework\TestCase;

class RestClientTest extends TestCase
{
    public $methodsClient;
    public $authenticator;

    public function setUp()
    {
        $this->methodsClient = \Mockery::mock(Client::class);
        $this->authenticator = \Mockery::mock(Authenticator::class);
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

        $this->methodsClient->shouldReceive('send')
            ->andReturn($response);
        $this->authenticator->shouldReceive('getAuthHeader')->andReturn([
            'Authorization' => 'Bearer 123abc',
        ]);

        $restClient = new RestClient($this->methodsClient, $this->authenticator);
        $result = $restClient->getReport($report);
        $this->assertSame($responseData, $result);
    }

    public function testRestClientBuilderHandlesClientCredentialsAuth()
    {
        $config = [
            'auth' => [
                'type' => ClientCredentials::TYPE,
                'appId' => '123abc',
                'appSecret' => 'cba321',
            ],
        ];
        $builder = new RestClientBuilder($config);
        $client = $builder->build();

        $this->assertInstanceOf(
            ClientCredentials::class,
            $client->getAuthenticator()->getAuthCredentials()
        );
    }

    public function testRestClientBuilderHandlesRefreshCredentialsAuth()
    {
        $config = [
            'auth' => [
                'type' => RefreshCredentials::TYPE,
                'appId' => '123abc',
                'appSecret' => 'cba321',
                'refreshToken' => 'fdasfdas',
            ],
        ];
        $builder = new RestClientBuilder($config);
        $client = $builder->build();

        $this->assertInstanceOf(
            RefreshCredentials::class,
            $client->getAuthenticator()->getAuthCredentials()
        );
    }

    public function testRestClientBuilderHandlesLegacyCredentialsAuth()
    {
        $config = [
            'auth' => [
                'type' => LegacyCredentials::TYPE,
                'clientId' => '123abc',
                'apiKey' => 'cba321',
            ],
        ];
        $builder = new RestClientBuilder($config);
        $client = $builder->build();

        $this->assertInstanceOf(
            LegacyCredentials::class,
            $client->getAuthenticator()->getAuthCredentials()
        );
    }

    public function testRestClientBuilderHandlesNullCredentialsAuth()
    {
        $config = [];
        $builder = new RestClientBuilder($config);
        $client = $builder->build();

        $this->assertInstanceOf(
            NullCredentials::class,
            $client->getAuthenticator()->getAuthCredentials()
        );
    }
}
