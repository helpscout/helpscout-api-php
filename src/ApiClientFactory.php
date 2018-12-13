<?php

declare(strict_types=1);

namespace HelpScout\Api;

use HelpScout\Api\Http\Authenticator;
use HelpScout\Api\Http\History;
use HelpScout\Api\Http\Plugin\AuthenticationPlugin;
use HelpScout\Api\Http\Plugin\RateLimitErrorPlugin;
use HelpScout\Api\Http\Plugin\ValidationErrorPlugin;
use HelpScout\Api\Http\RestClientBuilder;
use Http\Client\Common\Plugin\ErrorPlugin;
use Http\Client\Common\Plugin\HistoryPlugin;

class ApiClientFactory
{
    /**
     * The base URI for the API.
     */
    const BASE_URI = 'https://api.helpscout.net';

    /**
     * The user agent header template.
     */
    const CLIENT_USER_AGENT = 'Help Scout PHP API Client/%s (PHP %s)';

    /**
     * The current API client version.
     */
    const CLIENT_VERSION = '2.0.0';

    /**
     * @param string                 $baseUri
     * @param RestClientBuilder|null $restClientBuilder
     *
     * @return ApiClient
     */
    public static function createClient(
        string $baseUri = self::BASE_URI,
        RestClientBuilder $restClientBuilder = null
    ): ApiClient {
        // Create a default REST client builder if not specified
        $restClientBuilder = $restClientBuilder ?: new RestClientBuilder();

        // Set the base URI for all requests
        $restClientBuilder->setBaseUri($baseUri);

        // Set the user agent with client/platform information
        $restClientBuilder->setUserAgent(sprintf(self::CLIENT_USER_AGENT, self::CLIENT_VERSION, phpversion()));

        $authenticationPlugin = new AuthenticationPlugin();
        $restClientBuilder->addPlugin($authenticationPlugin);

        $restClientBuilder->addPlugin(new ErrorPlugin());

        // Specific error plugins must be added after the general error plugin
        $restClientBuilder->addPlugin(new ValidationErrorPlugin());
        $restClientBuilder->addPlugin(new RateLimitErrorPlugin());

        $history = new History();
        $restClientBuilder->addPlugin(new HistoryPlugin($history));

        return new ApiClient($restClientBuilder->build(), new Authenticator($authenticationPlugin), $history);
    }
}
