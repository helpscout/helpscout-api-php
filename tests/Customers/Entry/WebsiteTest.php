<?php

declare(strict_types=1);

namespace HelpScout\Api\Tests\Customers\Entry;

use HelpScout\Api\Customers\Entry\Website;
use PHPUnit\Framework\TestCase;

class WebsiteTest extends TestCase
{
    public function testHydrate()
    {
        $website = new Website();
        $website->hydrate([
            'id' => 12,
            'value' => 'https://www.helpscout.com',
        ]);

        $this->assertSame(12, $website->getId());
        $this->assertSame('https://www.helpscout.com', $website->getValue());
    }

    public function testExtract()
    {
        $website = new Website();
        $website->setId(12);
        $website->setValue('https://www.helpscout.com');

        $this->assertSame([
            'value' => 'https://www.helpscout.com',
        ], $website->extract());
    }

    public function testExtractNewEntity()
    {
        $website = new Website();

        $this->assertSame([
            'value' => null,
        ], $website->extract());
    }
}
