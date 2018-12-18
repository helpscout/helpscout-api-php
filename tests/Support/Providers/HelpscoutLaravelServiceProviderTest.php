<?php

declare(strict_types=1);

namespace HelpScout\Api\Tests\Support\Providers;

use HelpScout\Api\ApiClient;
use HelpScout\Api\Support\Facades\HelpScout;
use HelpScout\Api\Support\Providers\HelpscoutLaravelServiceProvider;
use HelpScout\Api\Workflows\WorkflowsEndpoint;
use Orchestra\Testbench\TestCase;

class HelpscoutLaravelServiceProviderTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [
            HelpscoutLaravelServiceProvider::class,
        ];
    }

    protected function getPackageAliases($app)
    {
        return [
            'HelpScout' => HelpScout::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('helpscout', [
            'auth' => [
                'type' => 'client_credentials',
                'appId' => '123abc',
                'appSecret' => 'cba321',
            ],
        ]);
    }

    public function testFacadeReturnsInstance()
    {
        $workflows = HelpScout::workflows();
        $this->assertInstanceOf(WorkflowsEndpoint::class, $workflows);
    }

    public function testContainerReturnsInstance()
    {
        $app = app();
        $app->register(HelpscoutLaravelServiceProvider::class);

        $client = $app->get(ApiClient::class);
        $this->assertInstanceOf(ApiClient::class, $client);

        $this->assertSame(
            $client,
            $app->get('helpscout')
        );
    }
}
