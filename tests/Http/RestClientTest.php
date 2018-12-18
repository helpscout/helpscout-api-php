<?php

declare(strict_types=1);

namespace HelpScout\Api\Tests\Http;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use HelpScout\Api\Http\Authenticator;
use HelpScout\Api\Http\RestClient;
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
}
