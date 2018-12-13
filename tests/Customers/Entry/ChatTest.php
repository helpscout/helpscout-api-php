<?php

declare(strict_types=1);

namespace HelpScout\Api\Tests\Customers\Entry;

use HelpScout\Api\Customers\Entry\Chat;
use PHPUnit\Framework\TestCase;

class ChatTest extends TestCase
{
    public function testHydrate()
    {
        $chat = new Chat();
        $chat->hydrate([
            'id' => 12,
            'value' => 'Hello there',
            'type' => 'twitter',
        ]);

        $this->assertSame(12, $chat->getId());
        $this->assertSame('Hello there', $chat->getValue());
        $this->assertSame('twitter', $chat->getType());
    }

    public function testExtract()
    {
        $chat = new Chat();
        $chat->setId(12);
        $chat->setValue('Hello there');
        $chat->setType('twitter');

        $this->assertSame([
            'value' => 'Hello there',
            'type' => 'twitter',
        ], $chat->extract());
    }

    public function testExtractNewEntity()
    {
        $chat = new Chat();

        $this->assertSame([
            'value' => null,
            'type' => null,
        ], $chat->extract());
    }
}
