<?php

declare(strict_types=1);

namespace HelpScout\Api\Tests\Customers\Entry;

use HelpScout\Api\Customers\Entry\ChatHandle;
use PHPUnit\Framework\TestCase;

class ChatHandleTest extends TestCase
{
    public function testHydrate()
    {
        $chatHandle = new ChatHandle();
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
        $chatHandle = new ChatHandle();
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
        $chatHandle = new ChatHandle();

        $this->assertSame([
            'value' => null,
            'type' => null,
        ], $chatHandle->extract());
    }
}
