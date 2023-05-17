<?php

declare(strict_types=1);

namespace HelpScout\Api\Http;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use HelpScout\Api\Http\Auth\Auth;
use HelpScout\Api\Http\Auth\ClientCredentials;
use HelpScout\Api\Http\Auth\HandlesTokenRefreshes;
use HelpScout\Api\Http\Auth\NullCredentials;
use HelpScout\Api\Http\Auth\RefreshCredentials;
use Http\Discovery\Psr18Client;
use Psr\Http\Client\ClientInterface;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpClient\Psr18Client as SymfonyClient;
use Symfony\Component\HttpClient\RetryableHttpClient;

/**
 * @internal
 */
class RestClientBuilder
{
    /**
     * @var array
     */
    private $config;

    /**
     * @var ClientInterface|null
     */
    private $client;

    public function __construct(array $config = [], ClientInterface $client = null)
    {
        $this->config = $config;
        $this->client = $client;
    }

    /**
     * @param \Closure|HandlesTokenRefreshes $tokenRefreshedCallback
     */
    public function build($tokenRefreshedCallback = null): RestClient
    {
        $client = $this->client ?? $this->getGuzzleClient() ?? $this->getSymfonyClient();
        $authenticator = $this->getAuthenticator($tokenRefreshedCallback, $client);

        return new RestClient(
            $client,
            $authenticator
        );
    }

    protected function getGuzzleClient(): ?Client
    {
        if (!class_exists(Client::class)) {
            return null;
        }

        $options = $this->getOptions();

        return new Client($options);
    }

    protected function getSymfonyClient(): ?SymfonyClient
    {
        if (!class_exists(RetryableHttpClient::class)) {
            return null;
        }

        return new SymfonyClient(new RetryableHttpClient(HttpClient::create()));
    }

    /**
     * @param \Closure|HandlesTokenRefreshes $tokenRefreshedCallback
     */
    protected function getAuthenticator($tokenRefreshedCallback = null, ClientInterface $client = null): Authenticator
    {
        $authConfig = $this->config['auth'] ?? [];

        $authenticator = new Authenticator(
            $client ?? new Client(),
            $this->getAuthClass($authConfig)
        );

        if ($tokenRefreshedCallback !== null) {
            $authenticator->callbackWhenTokenRefreshed($tokenRefreshedCallback);
        }

        return $authenticator;
    }

    protected function getAuthClass(array $authConfig = []): Auth
    {
        $type = $authConfig['type'] ?? '';

        switch ($type) {
            case ClientCredentials::TYPE:
                return new ClientCredentials(
                    $authConfig['appId'],
                    $authConfig['appSecret']
                );
            case RefreshCredentials::TYPE:
                return new RefreshCredentials(
                    $authConfig['appId'],
                    $authConfig['appSecret'],
                    $authConfig['refreshToken']
                );
            default:
                return new NullCredentials();
        }
    }

    protected function getOptions(): array
    {
        return [
            'handler' => $this->getHandlerStack(),
            'http_errors' => false,
        ];
    }

    protected function getHandlerStack(): HandlerStack
    {
        $handler = HandlerStack::create();

        $handler->push(Middleware::retry($this->getRetryDecider()));

        return $handler;
    }

    /**
     * Should we retry this failure?
     *
     * @return \Closure
     */
    protected function getRetryDecider(): callable
    {
        return function (
            $retries,
            Request $request,
            Response $response = null,
            GuzzleException $exception = null
        ) {
            // Don't retry unless this is a Connection issue
            if (!$exception instanceof ConnectException) {
                return false;
            }

            // Limit the number of retries
            return $retries < 4;
        };
    }
}
