<?php

declare(strict_types=1);

namespace HelpScout\Api\Tests\Chats;

use HelpScout\Api\Chats\TimelineEvent;
use PHPUnit\Framework\TestCase;

class TimelineEventTest extends TestCase
{
    public function testHydrate()
    {
        $event = new TimelineEvent();
        $event->hydrate([
            'type' => 'page-viewed',
            'timestamp' => '2017-04-21T14:39:56Z',
            'url' => 'https://www.helpscout.com',
            'title' => 'Help Scout',
        ]);

        $this->assertEquals('page-viewed', $event->getType());
        $this->assertInstanceOf(\DateTime::class, $event->getTimestamp());
        $this->assertSame('2017-04-21T14:39:56+00:00', $event->getTimestamp()->format('c'));
        $this->assertEquals('https://www.helpscout.com', $event->getUrl());
        $this->assertEquals('Help Scout', $event->getTitle());
    }

    public function testHydrateWithoutCreatedAt()
    {
        $event = new TimelineEvent();
        $event->hydrate([]);

        $this->assertNull($event->getTimestamp());
    }
}
