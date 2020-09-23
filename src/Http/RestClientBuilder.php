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
use HelpScout\Api\Http\Handlers\AuthenticationHandler;
use HelpScout\Api\Http\Handlers\ClientErrorHandler;
use HelpScout\Api\Http\Handlers\RateLimitHandler;
use HelpScout\Api\Http\Handlers\ValidationHandler;

class RestClientBuilder
{
    /**
     * @var array
     */
    private $config;

    public function __construct(array $config = [])
    {
        $this->config = $config;
    }

    /**
     * @param \Closure|HandlesTokenRefreshes $tokenRefreshedCallback
     */
    public function build($tokenRefreshedCallback = null): RestClient
    {
        $authenticator = $this->getAuthenticator($tokenRefreshedCallback);

        return new RestClient(
            $this->getGuzzleClient(),
            $authenticator
        );
    }

    protected function getGuzzleClient(): Client
    {
        $options = $this->getOptions();

        return new Client($options);
    }

    protected function getAuthenticator($tokenRefreshedCallback = null): Authenticator
    {
        $authConfig = $this->config['auth'] ?? [];

        $authenticator = new Authenticator(
            new Client(),
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

        $handler->push(new AuthenticationHandler());
        $handler->push(new ClientErrorHandler());
        $handler->push(new RateLimitHandler());
        $handler->push(new ValidationHandler());
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
