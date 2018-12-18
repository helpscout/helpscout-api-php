<?php

declare(strict_types=1);

namespace HelpScout\Api\Tests;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use HelpScout\Api\ApiClient;
use HelpScout\Api\Http\Authenticator;
use HelpScout\Api\Http\Handlers\ClientErrorHandler;
use HelpScout\Api\Http\Handlers\RateLimitHandler;
use HelpScout\Api\Http\Handlers\ValidationHandler;
use HelpScout\Api\Http\RestClient;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;

abstract class ApiClientIntegrationTestCase extends TestCase
{
    /**
     * @var array
     */
    protected $history = [];

    /**
     * @var MockHandler
     */
    protected $mockHandler;

    /**
     * @var Authenticator
     */
    protected $authenticator;

    /**
     * @var ApiClient
     */
    protected $client;

    public function setUp()
    {
        $this->history = [];
        $this->mockHandler = new MockHandler();

        $handler = HandlerStack::create($this->mockHandler);

        $handler->push(Middleware::history($this->history));
        $handler->push(new ClientErrorHandler());
        $handler->push(new RateLimitHandler());
        $handler->push(new ValidationHandler());

        $client = new Client(['handler' => $handler, 'http_errors' => false]);

        $this->authenticator = new Authenticator($client);
        $this->authenticator->setAccessToken('abc123');

        $this->client = new ApiClient(
            new RestClient($client, $this->authenticator)
        );
    }

    /**
     * @param array $responses
     */
    protected function stubResponses(array $responses): void
    {
        foreach ($responses as $response) {
            $this->mockHandler->append($response);
        }
    }

    /**
     * @param Response $response
     */
    protected function stubResponse(Response $response): void
    {
        $this->mockHandler->append($response);
    }

    /**
     * @param int    $status
     * @param string $body
     * @param array  $headers
     *
     * @return Response
     */
    protected function getResponse($status = 200, $body = '', $headers = []): Response
    {
        return new Response($status, $headers, $body);
    }

    protected function verifyMultipleRequests(array $expected): void
    {
        foreach ($expected as $key => $data) {
            [$expectedMethod, $expectedUri] = $data;

            $this->verifyRequestMethodAndUri(
                $this->history[$key]['request'],
                $expectedMethod,
                $expectedUri
            );
        }
    }

    protected function verifyMultipleRequestsWithData(array $expected): void
    {
        foreach ($expected as $key => $data) {
            [$expectedMethod, $expectedUri, $expectedData] = $data;

            $this->verifyRequestMethodAndUri(
                $this->history[$key]['request'],
                $expectedMethod,
                $expectedUri
            );

            $this->verifyRequestData($this->history[$key]['request'], $expectedData);
        }
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
        $this->assertCount(1, $this->history);

        $request = $this->history[0]['request'];
        $this->verifyRequestMethodAndUri(
            $request,
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
            $this->history[0]['request'],
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
