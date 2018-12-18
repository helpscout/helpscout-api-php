<?php

declare(strict_types=1);

namespace HelpScout\Api\Suppor\Providers;

use HelpScout\Api\ApiClient;
use Illuminate\Support\ServiceProvider;

/**
 * @codeCoverageIgnore
 */
class HelpscoutLaravelServiceProvider extends ServiceProvider
{
    /**
     * @var bool
     */
    protected $defer = true;

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
        $this->app->singleton(
            'helpscout',
            function () {
                $config = config('helpscout', []);

                return ApiClientFactory::createClient($config);
            }
        );
    }
}
