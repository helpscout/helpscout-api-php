<?php

declare(strict_types=1);

namespace HelpScout\Api\Tests\Users;

use DateTime;
use HelpScout\Api\Users\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testHydrate()
    {
        $user = new User();
        $user->hydrate([
            'id' => 12,
            'createdAt' => '2017-04-21T14:39:56Z',
            'updatedAt' => '2017-04-21T14:43:24Z',
            'firstName' => 'Big',
            'lastName' => 'Bird',
            'email' => 'bird@sesamestreet.com',
            'role' => 'owner',
            'timezone' => 'America/New_York',
            'photoUrl' => 'https://helpscout.com/images/avatar.jpg',
            'type' => 'user',
        ]);

        $this->assertSame(12, $user->getId());
        $this->assertInstanceOf(DateTime::class, $user->getCreatedAt());
        $this->assertSame('2017-04-21T14:39:56+00:00', $user->getCreatedAt()->format('c'));
        $this->assertInstanceOf(DateTime::class, $user->getUpdatedAt());
        $this->assertSame('2017-04-21T14:43:24+00:00', $user->getUpdatedAt()->format('c'));
        $this->assertSame('Big', $user->getFirstName());
        $this->assertSame('Bird', $user->getLastName());
        $this->assertSame('bird@sesamestreet.com', $user->getEmail());
        $this->assertSame('owner', $user->getRole());
        $this->assertSame('America/New_York', $user->getTimezone());
        $this->assertSame('https://helpscout.com/images/avatar.jpg', $user->getPhotoUrl());
        $this->assertSame('user', $user->getType());
    }

    public function testHydrateWithoutCreatedAt()
    {
        $user = new User();
        $user->hydrate([
            'id' => 12,
        ]);

        $this->assertNull($user->getCreatedAt());
    }

    public function testHydrateWithoutUpdatedAt()
    {
        $user = new User();
        $user->hydrate([
            'id' => 12,
        ]);

        $this->assertNull($user->getUpdatedAt());
    }

    public function testHydrateWithoutNameSuffixes()
    {
        $user = new User();
        $user->hydrate([
            'first' => 'John',
            'last' => 'Smith',
        ]);

        $this->assertSame('John', $user->getFirstName());
        $this->assertSame('Smith', $user->getLastName());
    }
}
