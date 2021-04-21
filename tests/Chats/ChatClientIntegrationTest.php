<?php

declare(strict_types=1);

namespace HelpScout\Api\Tests\Chats;

use HelpScout\Api\Chats\Chat;
use HelpScout\Api\Chats\Event;
use HelpScout\Api\Tests\ApiClientIntegrationTestCase;
use HelpScout\Api\Tests\Payloads\ChatPayloads;
use Ramsey\Uuid\Uuid;

/**
 * @group integration
 */
class ChatClientIntegrationTest extends ApiClientIntegrationTestCase
{
    public function testGetChat()
    {
        $chatId = (string) Uuid::uuid4();

        $this->stubResponse(
            $this->getResponse(200, ChatPayloads::getChat($chatId))
        );

        $chat = $this->client->chats()->get($chatId);

        $this->assertInstanceOf(Chat::class, $chat);

        $this->verifySingleRequest(
            "https://api.helpscout.net/chat/v1/$chatId"
        );
    }

    public function testGetEvents()
    {
        $chatId = (string) Uuid::uuid4();

        $this->stubResponse(
            $this->getResponse(200, ChatPayloads::getEvents(1, 10))
        );

        $events = $this->client->chats()->events($chatId);

        $this->assertCount(10, $events);
        $this->assertInstanceOf(Event::class, $events[0]);

        $this->verifySingleRequest(
            "https://api.helpscout.net/chat/v1/$chatId/events"
        );
    }
}
