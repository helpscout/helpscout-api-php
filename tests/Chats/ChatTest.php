<?php

declare(strict_types=1);

namespace HelpScout\Api\Tests\Chats;

use HelpScout\Api\Chats\Chat;
use HelpScout\Api\Chats\Event;
use HelpScout\Api\Chats\TimelineEvent;
use HelpScout\Api\Customers\Customer;
use HelpScout\Api\Entity\Collection;
use HelpScout\Api\Tags\Tag;
use HelpScout\Api\Users\User;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class ChatTest extends TestCase
{
    public function testHydrate()
    {
        $chatId = (string) Uuid::uuid4();
        $beaconId = (string) Uuid::uuid4();
        $eventId = (string) Uuid::uuid4();

        $chat = new Chat();
        $chat->hydrate([
            'id' => $chatId,
            'beaconId' => $beaconId,
            'mailboxId' => 4,
            'createdAt' => '2017-04-21T14:39:56Z',
            'endedAt' => '2017-04-21T14:40:56Z',
            'preview' => 'Preview text',
            'assignee' => [
                'id' => 1,
                'type' => 'user',
                'first' => 'Tom',
                'last' => 'Graham',
                'email' => 'tom@helpscout.com',
            ],
            'customer' => [
                'id' => 2,
                'type' => 'customer',
                'first' => 'Denny',
                'last' => 'Swindle',
                'email' => 'denny@helpscout.com',
            ],
            'tags' => [
                [
                    'id' => null,
                    'slug' => 'test',
                    'color' => 'none',
                ],
            ],
            'timeline' => [
                [
                    'type' => 'chat-started',
                    'timestamp' => '2021-04-12T08:44:34.542000Z',
                    'url' => 'https://fiddle.jshell.net',
                    'title' => 'Untitled Page',
                ],
            ],
        ], [
            'events' => [
                [
                    'id' => $eventId,
                    'type' => 'message',
                    'action' => 'message-added',
                    'author' => [
                        'id' => 12,
                    ],
                    'createdAt' => '2017-04-21T14:39:56Z',
                    'body' => 'Test message',
                    'params' => ['name' => 'value'],
                ],
            ],
        ]);

        $this->assertEquals($chatId, $chat->getId());
        $this->assertEquals($beaconId, $chat->getBeaconId());
        $this->assertEquals(4, $chat->getMailboxId());
        $this->assertInstanceOf(\DateTime::class, $chat->getCreatedAt());
        $this->assertSame('2017-04-21T14:39:56+00:00', $chat->getCreatedAt()->format('c'));
        $this->assertInstanceOf(\DateTime::class, $chat->getEndedAt());
        $this->assertSame('2017-04-21T14:40:56+00:00', $chat->getEndedAt()->format('c'));
        $this->assertEquals('Preview text', $chat->getPreview());
        $this->assertInstanceOf(User::class, $chat->getAssignee());
        $this->assertInstanceOf(Customer::class, $chat->getCustomer());

        $tags = $chat->getTags();
        $this->assertInstanceOf(Collection::class, $tags);
        $this->assertCount(1, $tags);
        $this->assertInstanceOf(Tag::class, $tags[0]);

        $timeline = $chat->getTimeline();
        $this->assertInstanceOf(Collection::class, $timeline);
        $this->assertCount(1, $timeline);
        $this->assertInstanceOf(TimelineEvent::class, $timeline[0]);

        $events = $chat->getEvents();
        $this->assertInstanceOf(Collection::class, $events);
        $this->assertCount(1, $events);
        $this->assertInstanceOf(Event::class, $events[0]);
    }

    public function testHydrateWithoutAssignee()
    {
        $chat = new Chat();
        $chat->hydrate([
            'id' => (string) Uuid::uuid4(),
        ]);

        $this->assertNull($chat->getAssignee());
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
