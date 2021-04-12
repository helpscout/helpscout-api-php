<?php

declare(strict_types=1);

namespace HelpScout\Api\Tests\Chats;

use HelpScout\Api\Chats\Event;
use HelpScout\Api\Users\User;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class EventTest extends TestCase
{
    public function testHydrate()
    {
        $eventId = (string) Uuid::uuid4();

        $event = new Event();
        $event->hydrate([
            'id' => $eventId,
            'type' => 'message',
            'action' => 'message-added',
            'author' => [
                'id' => 12,
            ],
            'createdAt' => '2017-04-21T14:39:56Z',
            'body' => 'Test message',
            'params' => ['name' => 'value'],
        ]);

        $this->assertEquals($eventId, $event->getId());
        $this->assertEquals('message', $event->getType());
        $this->assertEquals('message-added', $event->getAction());
        $this->assertInstanceOf(\DateTime::class, $event->getCreatedAt());
        $this->assertSame('2017-04-21T14:39:56+00:00', $event->getCreatedAt()->format('c'));
        $this->assertEquals('Test message', $event->getBody());
        $this->assertEquals(['name' => 'value'], $event->getParams());

        $this->assertInstanceOf(User::class, $event->getAuthor());
        $this->assertEquals(12, $event->getAuthor()->getId());
    }

    public function testHydrateWithoutAuthor()
    {
        $event = new Event();
        $event->hydrate([
            'id' => (string) Uuid::uuid4(),
        ]);

        $this->assertNull($event->getAuthor());
    }

    public function testHydrateWithoutCreatedAt()
    {
        $event = new Event();
        $event->hydrate([
            'id' => (string) Uuid::uuid4(),
        ]);

        $this->assertNull($event->getCreatedAt());
    }
}
