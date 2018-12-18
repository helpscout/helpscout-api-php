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
        return [
            ApiClient::class,
            'helpscout',
        ];
    }

    /**
     * Register any application services.
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/helpscout.php' => config_path('helpscout.php'),
        ]);
    }

    /**
     * Register the service provider.
     *
     * @throws \RuntimeException
     */
    public function register()
    {
        $config = config('helpscout', []);

        $this->app->singleton(ApiClient::class, function ($app) use ($config) {
            return  ApiClientFactory::createClient($config);
        });

        $this->app->alias(ApiClient::class, 'helpscout');
    }
}
