<?php

declare(strict_types=1);

namespace HelpScout\Api\Tests\Teams;

use HelpScout\Api\Chats\Chat;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class ChatTest extends TestCase
{
    public function testHydrate()
    {
        $chat = new Chat();
        $chat->hydrate([
        ]);

    }

    public function testHydrateWithoutCreatedAt()
    {
        $chat = new Chat();
        $chat->hydrate([
            'id' => (string) Uuid::uuid4(),
        ]);

        $this->assertNull($chat->getCreatedAt());
    }

    public function testHydrateWithoutEndedAt()
    {
        $chat = new Chat();
        $chat->hydrate([
            'id' => 12,
        ]);

        $this->assertNull($chat->getEndedAt());
    }
}
