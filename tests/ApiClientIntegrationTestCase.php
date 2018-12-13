<?php

declare(strict_types=1);

namespace HelpScout\Api\Tests;

use GuzzleHttp\Psr7\Response;
use HelpScout\Api\ApiClient;
use HelpScout\Api\ApiClientFactory;
use HelpScout\Api\Http\RestClientBuilder;
use Http\Mock\Client as MockHttpClient;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;

abstract class ApiClientIntegrationTestCase extends TestCase
{
    /**
     * @var MockHttpClient
     */
    protected $mockHttpClient;

    /**
     * @var ApiClient
     */
    protected $client;

    public function setUp()
    {
        $this->mockHttpClient = new MockHttpClient();

        $restClientBuilder = new RestClientBuilder($this->mockHttpClient);
        $this->client = ApiClientFactory::createClient('https://api.helpscout.net', $restClientBuilder);
        $this->client->setAccessToken('secret');
    }

    /**
     * @param int    $status
     * @param string $body
     * @param array  $headers
     */
    protected function stubResponse(int $status, string $body = '', array $headers = []): void
    {
        $this->mockHttpClient->addResponse(new Response($status, $headers, $body));
    }

    protected function verifyMultpleRequests(array $expected): void
    {
        $requests = $this->mockHttpClient->getRequests();
        foreach ($expected as $key => $data) {
            [$expectedMethod, $expectedUri] = $data;

            $this->verifyRequestMethodAndUri(
                $requests[$key],
                $expectedMethod,
                $expectedUri
            );
        }
    }

    protected function verifyMultipleRequestsWithData(array $expected): void
    {
        $requests = $this->mockHttpClient->getRequests();
        foreach ($expected as $key => $data) {
            [$expectedMethod, $expectedUri, $expectedData] = $data;

            $this->verifyRequestMethodAndUri(
                $requests[$key],
                $expectedMethod,
                $expectedUri
            );

            $this->verifyRequestData($requests[$key], $expectedData);
        }
    }

    protected function verifyMultipleRequestCount(array $expected): void
    {
        /** @var array $requests */
        $requests = $this->mockHttpClient->getRequests();
        $expectedTotalRequests = \count($expected);
        $this->assertCount($expectedTotalRequests, $requests);
    }

    protected function verifyRequestMethodAndUri(
        RequestInterface $request,
        string $expectedMethod,
        string $expectedUri
    ): void {
        $method = $request->getMethod();
        $uri = (string) $request->getUri();

        $this->assertSame($expectedMethod, $method);
        $this->assertSame($expectedUri, $uri);
    }

    protected function verifySingleRequest(
        string $expectedUri,
        string $expectedMethod = 'GET'
    ): void {
        /** @var array $requests */
        $requests = $this->mockHttpClient->getRequests();

        $this->assertCount(1, $requests);

        $this->verifyRequestMethodAndUri(
            $requests[0],
            $expectedMethod,
            $expectedUri
        );
    }

    protected function verifyRequestWithData(
        string $expectedUri,
        string $expectedMethod = 'GET',
        array $data = []
    ): void {
        $this->verifySingleRequest($expectedUri, $expectedMethod);

        $this->verifyRequestData(
            $this->mockHttpClient->getLastRequest(),
            $data
        );
    }

    protected function verifyRequestData(
        RequestInterface $request,
        array $expectedData
    ): void {
        $requestBody = (string) $request->getBody();

        $this->assertSame(
            $expectedData,
            json_decode($requestBody, true)
        );
    }
}
