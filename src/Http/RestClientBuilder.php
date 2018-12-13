<?php

declare(strict_types=1);

namespace HelpScout\Api\Http;

use Http\Client\Common\HttpMethodsClient;
use Http\Client\Common\Plugin;
use Http\Client\Common\Plugin\AddHostPlugin;
use Http\Client\Common\Plugin\HeaderDefaultsPlugin;
use Http\Client\Common\PluginClient;
use Http\Client\HttpClient;
use Http\Discovery\HttpClientDiscovery;
use Http\Discovery\MessageFactoryDiscovery;
use Http\Discovery\StreamFactoryDiscovery;
use Http\Discovery\UriFactoryDiscovery;
use Http\Message\RequestFactory;
use Http\Message\StreamFactory;

class RestClientBuilder
{
    /**
     * @var HttpClient
     */
    private $httpClient;

    /**
     * @var RequestFactory
     */
    private $requestFactory;

    /**
     * @var StreamFactory
     */
    private $streamFactory;

    /**
     * @var array
     */
    private $plugins;

    /**
     * @param HttpClient|null     $httpClient
     * @param RequestFactory|null $requestFactory
     * @param StreamFactory|null  $streamFactory
     */
    public function __construct(
        HttpClient $httpClient = null,
        RequestFactory $requestFactory = null,
        StreamFactory $streamFactory = null
    ) {
        $this->httpClient = $httpClient ?: HttpClientDiscovery::find();
        $this->requestFactory = $requestFactory ?: MessageFactoryDiscovery::find();
        $this->streamFactory = $streamFactory ?: StreamFactoryDiscovery::find();
    }

    /**
     * @return RestClient
     */
    public function build(): RestClient
    {
        return new RestClient(
            new HttpMethodsClient(
                new PluginClient($this->httpClient, $this->plugins),
                $this->requestFactory
            )
        );
    }

    /**
     * @param Plugin $plugin
     */
    public function addPlugin(Plugin $plugin)
    {
        $this->plugins[] = $plugin;
    }

    /**
     * @param string $baseUri
     */
    public function setBaseUri(string $baseUri)
    {
        $baseUri = UriFactoryDiscovery::find()->createUri($baseUri);
        $this->addPlugin(new AddHostPlugin($baseUri));
    }

    /**
     * @param string $userAgent
     */
    public function setUserAgent(string $userAgent)
    {
        $this->addPlugin(new HeaderDefaultsPlugin([
            'User-Agent' => $userAgent,
        ]));
    }
}
