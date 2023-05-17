<?php

declare(strict_types=1);

namespace HelpScout\Api;

use Closure;
use HelpScout\Api\Http\Auth\HandlesTokenRefreshes;
use HelpScout\Api\Http\RestClientBuilder;
use Psr\Http\Client\ClientInterface;

class ApiClientFactory
{
    /**
     * @param Closure|HandlesTokenRefreshes $tokenRefreshedCallback
     */
    public static function createClient(
        array $config = [],
        $tokenRefreshedCallback = null,
        ClientInterface $client = null
    ): ApiClient {
        $restClientBuilder = new RestClientBuilder($config, $client);

        return new ApiClient($restClientBuilder->build($tokenRefreshedCallback));
    }
}
