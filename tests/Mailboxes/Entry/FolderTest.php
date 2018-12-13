<?php

declare(strict_types=1);

namespace HelpScout\Api\Tests\Mailboxes\Entry;

use DateTime;
use HelpScout\Api\Mailboxes\Entry\Folder;
use PHPUnit\Framework\TestCase;

class FolderTest extends TestCase
{
    public function testHydrate()
    {
        $folder = new Folder();
        $folder->hydrate([
            'id' => 12,
            'name' => 'My Tickets',
            'type' => 'mytickets',
            'userId' => 1,
            'totalCount' => 200,
            'activeCount' => 100,
            'updatedAt' => '2017-04-21T14:43:24Z',
        ]);

        $this->assertSame(12, $folder->getId());
        $this->assertSame('My Tickets', $folder->getName());
        $this->assertSame('mytickets', $folder->getType());
        $this->assertSame(1, $folder->getUserId());
        $this->assertSame(200, $folder->getTotalCount());
        $this->assertSame(100, $folder->getActiveCount());

        $this->assertInstanceOf(DateTime::class, $folder->getUpdatedAt());
        $this->assertSame('2017-04-21T14:43:24+00:00', $folder->getUpdatedAt()->format('c'));
    }
}
