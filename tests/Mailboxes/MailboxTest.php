<?php

declare(strict_types=1);

namespace HelpScout\Api\Tests\Mailboxes;

use DateTime;
use HelpScout\Api\Mailboxes\Entry\Field;
use HelpScout\Api\Mailboxes\Entry\Folder;
use HelpScout\Api\Mailboxes\Mailbox;
use PHPUnit\Framework\TestCase;

class MailboxTest extends TestCase
{
    public function testHydrate()
    {
        $mailbox = new Mailbox();
        $mailbox->hydrate([
            'id' => 12,
            'createdAt' => '2017-04-21T14:39:56Z',
            'updatedAt' => '2017-04-21T14:43:24Z',
            'name' => 'Support',
            'slug' => 'support',
            'email' => 'support@sesamestreet.com',
        ]);

        $this->assertSame(12, $mailbox->getId());
        $this->assertInstanceOf(DateTime::class, $mailbox->getCreatedAt());
        $this->assertSame('2017-04-21T14:39:56+00:00', $mailbox->getCreatedAt()->format('c'));
        $this->assertInstanceOf(DateTime::class, $mailbox->getUpdatedAt());
        $this->assertSame('2017-04-21T14:43:24+00:00', $mailbox->getUpdatedAt()->format('c'));
        $this->assertSame('Support', $mailbox->getName());
        $this->assertSame('support', $mailbox->getSlug());
        $this->assertSame('support@sesamestreet.com', $mailbox->getEmail());
    }

    public function testHydrateWithoutCreatedAt()
    {
        $mailbox = new Mailbox();
        $mailbox->hydrate([
            'id' => 12,
            'updatedAt' => '2017-04-21T14:43:24Z',
            'name' => 'Support',
            'slug' => 'support',
            'email' => 'support@sesamestreet.com',
        ]);

        $this->assertNull($mailbox->getCreatedAt());
    }

    public function testHydrateWithoutUpdatedAt()
    {
        $mailbox = new Mailbox();
        $mailbox->hydrate([
            'id' => 12,
            'createdAt' => '2017-04-21T14:39:56Z',
            'name' => 'Support',
            'slug' => 'support',
            'email' => 'support@sesamestreet.com',
        ]);

        $this->assertNull($mailbox->getUpdatedAt());
    }

    public function testAddMethods()
    {
        $mailbox = new Mailbox();

        $this->assertEmpty($mailbox->getFields()->toArray());
        $this->assertEmpty($mailbox->getFolders()->toArray());

        $field = new Field();
        $mailbox->addField($field);
        $this->assertSame($field, $mailbox->getFields()->toArray()[0]);

        $folder = new Folder();
        $mailbox->addFolder($folder);
        $this->assertSame($folder, $mailbox->getFolders()->toArray()[0]);
    }
}
