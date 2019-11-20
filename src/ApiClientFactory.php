<?php

declare(strict_types=1);

namespace HelpScout\Api;

use HelpScout\Api\Http\RestClientBuilder;

class ApiClientFactory
{
    public static function createClient(array $config = []): ApiClient
    {
        $restClientBuilder = new RestClientBuilder($config);

        return new ApiClient($restClientBuilder->build());
    }
}
