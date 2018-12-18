<?php

declare(strict_types=1);

namespace HelpScout\Api\Tests;

use HelpScout\Api\ApiClient;
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

        $this->client = new ApiClient($restClient);
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
}
