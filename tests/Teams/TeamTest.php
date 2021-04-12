<?php

declare(strict_types=1);

namespace HelpScout\Api\Tests\Teams;

use DateTime;
use HelpScout\Api\Teams\Team;
use PHPUnit\Framework\TestCase;

class TeamTest extends TestCase
{
    public function testHydrate()
    {
        $team = new Team();
        $team->hydrate([
            'id' => 12,
            'name' => 'Sarah',
            'timezone' => 'America/New_York',
            'photoUrl' => 'https://helpscout.com/images/avatar.jpg',
            'createdAt' => '2017-04-21T14:39:56Z',
            'updatedAt' => '2017-04-21T14:43:24Z',
            'mention' => 'sjones',
            'initials' => 's',
        ]);

        $this->assertSame(12, $team->getId());
        $this->assertInstanceOf(DateTime::class, $team->getCreatedAt());
        $this->assertSame('2017-04-21T14:39:56+00:00', $team->getCreatedAt()->format('c'));
        $this->assertInstanceOf(DateTime::class, $team->getUpdatedAt());
        $this->assertSame('2017-04-21T14:43:24+00:00', $team->getUpdatedAt()->format('c'));
        $this->assertSame('Sarah', $team->getName());
        $this->assertSame('America/New_York', $team->getTimezone());
        $this->assertSame('https://helpscout.com/images/avatar.jpg', $team->getPhotoUrl());
        $this->assertSame('sjones', $team->getMention());
        $this->assertSame('s', $team->getInitials());
    }

    public function testHydrateWithoutCreatedAt()
    {
        $team = new Team();
        $team->hydrate([
            'id' => 12,
        ]);

        $this->assertNull($team->getCreatedAt());
    }

    public function testHydrateWithoutUpdatedAt()
    {
        $team = new Team();
        $team->hydrate([
            'id' => 12,
        ]);

        $this->assertNull($team->getUpdatedAt());
    }
}
