<?php

declare(strict_types=1);

namespace HelpScout\Api\Tests\Http\Hal;

use HelpScout\Api\Exception\InvalidArgumentException;
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
}
