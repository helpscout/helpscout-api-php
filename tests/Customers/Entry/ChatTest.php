<?php

declare(strict_types=1);

namespace HelpScout\Api\Tests\Customers\Entry;

use HelpScout\Api\Customers\Entry\Chat;
use PHPUnit\Framework\TestCase;

/**
 * Tests for Chart are initially the same as ChatHandle.  We're duplicating the tests here because ChatHandle
 * may see additional functionality in the future and its tests/assertions change, whereas these shouldn't.
 */
class ChatTest extends TestCase
{
    public function testHydrate()
    {
        $chatHandle = new Chat();
        $chatHandle->hydrate([
            'id' => 12,
            'value' => 'Hello there',
            'type' => 'twitter',
        ]);

        $this->assertSame(12, $chatHandle->getId());
        $this->assertSame('Hello there', $chatHandle->getValue());
        $this->assertSame('twitter', $chatHandle->getType());
    }

    public function testExtract()
    {
        $chatHandle = new Chat();
        $chatHandle->setId(12);
        $chatHandle->setValue('Hello there');
        $chatHandle->setType('twitter');

        $this->assertSame([
            'value' => 'Hello there',
            'type' => 'twitter',
        ], $chatHandle->extract());
    }

    public function testExtractNewEntity()
    {
        $chatHandle = new Chat();

        $this->assertSame([
            'value' => null,
            'type' => null,
        ], $chatHandle->extract());
    }
}
