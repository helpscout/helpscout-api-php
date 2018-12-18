<?php

declare(strict_types=1);

namespace HelpScout\Api\Tests\Support\Providers;

use HelpScout\Api\ApiClient;
use HelpScout\Api\Support\Providers\HelpscoutLeagueServiceProvider;
use League\Container\Container;
use PHPUnit\Framework\TestCase;

class HelpscoutLeagueServiceProviderTest extends TestCase
{
    public function testProviderRegistersWithContainer()
    {
        $container = new Container();
        $container->addServiceProvider(new HelpscoutLeagueServiceProvider());

        $client = $container->get('helpscout');
        $this->assertInstanceOf(ApiClient::class, $client);

        $this->assertSame($client, $container->get(ApiClient::class));
    }
}
