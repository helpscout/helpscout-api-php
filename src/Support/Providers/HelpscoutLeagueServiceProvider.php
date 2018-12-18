<?php

declare(strict_types=1);

namespace HelpScout\Api\Support\Providers;

use HelpScout\Api\ApiClient;
use League\Container\ServiceProvider\AbstractServiceProvider;

/**
 * @codeCoverageIgnore
 */
class HelpscoutLeagueServiceProvider extends AbstractServiceProvider
{
    /**
     * @return array
     */
    public function provides()
    {
        return [
            ApiClient::class,
            'helpscout',
        ];
    }

    /**
     * Register the service provider.
     *
     * @throws \RuntimeException
     */
    public function register()
    {
        $this->getContainer()->share(
            'helpscout',
            function () {
                return ApiClientFactory::createClient();
            }
        );
    }
}
