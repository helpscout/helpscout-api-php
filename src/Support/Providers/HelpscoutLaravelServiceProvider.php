<?php

declare(strict_types=1);

namespace HelpScout\Api\Support\Providers;

use HelpScout\Api\ApiClient;
use HelpScout\Api\ApiClientFactory;
use Illuminate\Support\ServiceProvider;

/**
 * @codeCoverageIgnore
 */
class HelpscoutLaravelServiceProvider extends ServiceProvider
{
    /**
     * @return array
     */
    public function provides()
    {
        $clientKeys = [
            ApiClient::class,
            'helpscout',
        ];

        return \array_merge(
            $clientKeys,
            \array_values(ApiClient::AVAILABLE_ENDPOINTS),
            \array_keys(ApiClient::AVAILABLE_ENDPOINTS)
        );
    }

    /**
     * Register any application services.
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../../../../config/helpscout.php' => config_path('helpscout.php'),
        ], 'helpscout');
    }

    /**
     * Register the service provider.
     *
     * @throws \RuntimeException
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../../../config/helpscout.php', 'courier'
        );

        $this->registerApiClient();
        $this->registerEndpoints();
    }

    protected function registerApiClient(): void
    {
        $config = config('helpscout', []);

        $this->app->singleton(ApiClient::class, function ($app) use ($config) {
            return  ApiClientFactory::createClient($config);
        });

        $this->app->alias(ApiClient::class, 'helpscout');
    }

    protected function registerEndpoints(): void
    {
        $client = $this->app->get(ApiClient::class);

        foreach (ApiClient::AVAILABLE_ENDPOINTS as $alias => $endpoint) {
            $method = $method = \str_replace('hs.', '', $alias);
            $concrete = $client->{$method}();

            $this->app->singleton($endpoint, function ($app) use ($concrete) {
                return $concrete;
            });
            $this->app->alias($endpoint, $alias);
        }
    }
}
