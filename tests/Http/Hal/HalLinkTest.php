<?php

declare(strict_types=1);

namespace HelpScout\Api\Tests\Http\Hal;

use HelpScout\Api\Exception\RuntimeException;
use HelpScout\Api\Http\Hal\HalLink;
use PHPUnit\Framework\TestCase;

class HalLinkTest extends TestCase
{
    public function testExpandTemplate()
    {
        $link = new HalLink('page', 'https://api.helpscout.net/v2/customers{?page}', true);
        $this->assertSame('https://api.helpscout.net/v2/customers?page=3', $link->expand(['page' => 3]));
    }

    public function testExpandNonTemplateThrowsException()
    {
        $this->expectException(RuntimeException::class);

        $link = new HalLink('self', 'https://api.helpscout.net/v2/customers', false);
        $link->expand(['broken' => true]);
    }
}
