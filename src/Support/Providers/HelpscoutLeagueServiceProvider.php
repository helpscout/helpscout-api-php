<?php

declare(strict_types=1);

namespace HelpScout\Api\Support\Providers;

use HelpScout\Api\ApiClient;
use HelpScout\Api\ApiClientFactory;
use League\Container\ServiceProvider\AbstractServiceProvider;

/**
 * @codeCoverageIgnore
 */
class HelpscoutLeagueServiceProvider extends AbstractServiceProvider
{
    /**
     * @return array
     */
    protected $provides = [
        ApiClient::class,
        'helpscout',
    ];

    /**
     * Register the service provider.
     *
     * @throws \RuntimeException
     */
    public function register()
    {
        $client = ApiClientFactory::createClient();
        $this->getContainer()->share(
            'helpscout',
            $client
        );

        $this->getContainer()->share(
            ApiClient::class,
            $client
        );
    }
}
