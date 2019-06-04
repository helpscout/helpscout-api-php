<?php

declare(strict_types=1);

namespace HelpScout\Api\Tests\Http\Hal;

use HelpScout\Api\Exception\InvalidArgumentException;
use HelpScout\Api\Http\Hal\HalLink;
use HelpScout\Api\Http\Hal\HalLinks;
use PHPUnit\Framework\TestCase;

class HalLinksTest extends TestCase
{
    public function testGetThrowsExceptionWhenLinkNotFound()
    {
        $this->expectException(InvalidArgumentException::class);

        $links = new HalLinks();
        $links->get('unknown');
    }

    public function testCountsCollectionSize()
    {
        $this->assertFalse((new HalLinks())->size());

        $links = new HalLinks();
        $links->add(new HalLink('rel', 'href', false));
        $this->assertFalse($links->size());
    }
}
