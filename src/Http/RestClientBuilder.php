<?php

declare(strict_types=1);

namespace HelpScout\Api\Http;

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use HelpScout\Api\Http\Auth\Auth;
use HelpScout\Api\Http\Auth\ClientCredentials;
use HelpScout\Api\Http\Auth\LegacyCredentials;
use HelpScout\Api\Http\Auth\NullCredentials;
use HelpScout\Api\Http\Handlers\Handlers;

class RestClientBuilder
{
    /**
     * @var array
     */
    private $config;

    /**
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $this->config = $config;
    }

    /**
     * @return RestClient
     */
    public function build(): RestClient
    {
        return new RestClient(
            $this->getGuzzleClient(),
            $this->getAuthenticator()
        );
    }

    /**
     * @return Client
     */
    protected function getGuzzleClient(): Client
    {
        $options = $this->getOptions();

        return new Client($options);
    }

    /**
     * @return Authenticator
     */
    protected function getAuthenticator(): Authenticator
    {
        $authConfig = $this->config['auth'] ?? [];

        return new Authenticator(
            new Client(),
            $this->getAuthClass($authConfig)
        );
    }

    /**
     * @param array $authConfig
     *
     * @return Auth
     */
    protected function getAuthClass(array $authConfig = []): Auth
    {
        $type = $authConfig['type'] ?? '';

        switch ($type) {
            case ClientCredentials::TYPE:
                return new ClientCredentials(
                    $authConfig['appId'],
                    $authConfig['appSecret']
                );
            case LegacyCredentials::TYPE:
                return new LegacyCredentials(
                    $authConfig['clientId'],
                    $authConfig['apiKey']
                );
            default:
                return new NullCredentials();
        }
    }

    /**
     * @return array
     */
    protected function getOptions(): array
    {
        $stack = $this->getHandlerStack();

        $options = [
            'handler' => $stack,
        ];

        if (isset($this->config['base_uri'])) {
            $options['base_uri'] = $this->config['base_uri'];
        }

        return $options;
    }

    /**
     * @return HandlerStack
     */
    protected function getHandlerStack(): HandlerStack
    {
        $handlerStack = HandlerStack::create();

        $handlerStack->push(
            Handlers::rateLimit()
        );

        $handlerStack->push(
            Handlers::validation()
        );

//        $handlerStack->push(
//            Handlers::clientError()
//        );

        return $handlerStack;
    }
}
