<?php

declare(strict_types=1);

namespace HelpScout\Api\Tests\Conversations\Threads;

use DateTime;
use DateTimeInterface;
use HelpScout\Api\Conversations\Threads\Attachments\Attachment;
use HelpScout\Api\Conversations\Threads\Thread;
use HelpScout\Api\Customers\Customer;
use HelpScout\Api\Exception\RuntimeException;
use HelpScout\Api\Users\User;
use PHPUnit\Framework\TestCase;

class ThreadTest extends TestCase
{
    public function testHydrate()
    {
        $thread = new Thread();
        $thread->hydrate([
            'id' => 12,
            'type' => 'customer',
            'status' => 'active',
            'state' => 'published',
            'action' => [
                'type' => 'manual-workflow',
                'text' => 'You ran the Assign to Spam workflow',
            ],
            'text' => 'Need Help',
            'source' => [
                'type' => 'email',
                'via' => 'user',
            ],
            'customer' => [
                'id' => 256,
                'first' => 'Mr',
                'last' => 'Robot',
                'email' => 'none@nowhere.com',
            ],
            'createdBy' => $createdBy = [
                'id' => 12,
                'type' => 'customer',
                'email' => 'bear@acme.com',
            ],
            'assignedTo' => $assignedTo = [
                'id' => 1234,
                'type' => 'team',
                'first' => 'Jack',
                'last' => 'Sprout',
                'email' => 'bear@acme.com',
            ],
            'savedReplyId' => 17142,
            'to' => [
                'bird@normal.com',
            ],
            'cc' => [
                'bear@normal.com',
            ],
            'bcc' => [
                'bear@secret.com',
            ],
            'imported' => true,
            'createdAt' => '2017-04-21T14:39:56Z',
            'openedAt' => '2017-04-21T14:12:56Z',
        ]);

        $this->assertSame(12, $thread->getId());
        $this->assertSame('active', $thread->getStatus());
        $this->assertSame('published', $thread->getState());
        $this->assertSame('manual-workflow', $thread->getActionType());
        $this->assertSame('You ran the Assign to Spam workflow', $thread->getActionText());
        $this->assertSame('Need Help', $thread->getText());
        $this->assertSame('email', $thread->getSourceType());
        $this->assertSame('user', $thread->getSourceVia());

        $this->assertSame(17142, $thread->getSavedReplyId());

        $this->assertInstanceOf(DateTimeInterface::class, $thread->getCreatedAt());
        $this->assertSame('2017-04-21T14:39:56+00:00', $thread->getCreatedAt()->format('c'));
        $this->assertSame('2017-04-21T14:12:56+00:00', $thread->getOpenedAt()->format('c'));

        $this->assertSame([
            'bird@normal.com',
        ], $thread->getTo());
        $this->assertSame([
            'bear@normal.com',
        ], $thread->getCC());
        $this->assertSame([
            'bear@secret.com',
        ], $thread->getBCC());
        $this->assertTrue($thread->isImported());
    }

    public function testHydrateTextFromBody()
    {
        $thread = new Thread();
        $thread->hydrate([
            'text' => 'Need Help',
        ]);

        $this->assertSame('Need Help', $thread->getText());
    }

    public function testDefaultsNotImported()
    {
        $thread = new Thread();
        $this->assertFalse($thread->isImported());
    }

    public function testhydratesToField()
    {
        $thread = new Thread();
        $thread->hydrate([
            'to' => 'test@test.com',
        ]);

        $user = new User();
        $user->setId(9865);
        $thread->setCreatedByUser($user);

        $extracted = $thread->extract();

        $this->assertEquals([
            'test@test.com',
        ], $extracted['to']);
    }

    public function testHydrateAttachments()
    {
        $thread = new Thread();
        $thread->hydrate([], [
            'attachments' => [
                [
                    'id' => 583,
                ],
            ],
        ]);

        $attachment = $thread->getAttachments()->toArray()[0];
        $this->assertSame(583, $attachment->getId());
    }

    public function testExtractsAttachments()
    {
        $thread = new Thread();
        $thread->hydrate([], [
            'attachments' => [
                [
                    'id' => 583,
                ],
            ],
        ]);

        $data = $thread->extract();

        $this->assertArrayHasKey('attachments', $data);
        $this->assertSame(583, $data['attachments'][0]['id']);
    }

    public function testExtract()
    {
        $thread = new Thread();
        $thread->hydrate([
            'id' => 12,
            'status' => 'active',
            'state' => 'published',
            'action' => [
                'type' => 'manual-workflow',
                'text' => 'You ran the Assign to Spam workflow',
            ],
            'assignedTo' => [
                'id' => 1234,
                'type' => 'team',
                'first' => 'Jack',
                'last' => 'Sprout',
                'email' => 'bear@acme.com',
            ],
            'savedReplyId' => 17142,
            'openedAt' => '2017-04-21T14:12:56Z',
            'to' => [
                'bird@normal.com',
            ],
            'imported' => 1,
        ]);
        $thread->setText('Need Help');
        $thread->setSourceType('email');
        $thread->setSourceVia('user');

        $customer = new Customer();
        $customer->hydrate([
            'id' => 6857,
            'email' => 'bear@acme.com',
        ]);
        $thread->setCreatedByCustomer($customer);

        $thread->setCreatedAt(new DateTime('2017-04-21T14:39:56Z'));
        $thread->setCC([
            'bear@normal.com',
        ]);
        $thread->setBCC([
            'bear@secret.com',
        ]);

        $this->assertEquals([
            'id' => 12,
            'status' => 'active',
            'state' => 'published',
            'action' => [
                'type' => 'manual-workflow',
                'text' => 'You ran the Assign to Spam workflow',
            ],
            'text' => 'Need Help',
            'source' => [
                'type' => 'email',
                'via' => 'user',
            ],
            'createdBy' => [
                'id' => 6857,
                'type' => 'customer',
            ],
            'assignedTo' => [
                'id' => 1234,
                'type' => 'team',
                'first' => 'Jack',
                'last' => 'Sprout',
                'email' => 'bear@acme.com',
            ],
            'savedReplyId' => 17142,
            'to' => [
                'bird@normal.com',
            ],
            'cc' => [
                'bear@normal.com',
            ],
            'bcc' => [
                'bear@secret.com',
            ],
            'createdAt' => '2017-04-21T14:39:56Z',
            'openedAt' => '2017-04-21T14:12:56Z',
            'imported' => true,
        ], $thread->extract());
    }

    public function testExtractsCreatedByUser()
    {
        $thread = new Thread();

        $user = new User();
        $user->setId(9865);
        $thread->setCreatedByUser($user);

        $extracted = $thread->extract();

        $this->assertArrayHasKey('createdBy', $extracted);
        $this->assertEquals([
            'id' => 9865,
            'type' => 'user',
        ], $extracted['createdBy']);
    }

    public function testExtractNewEntity()
    {
        $thread = new Thread();

        $this->assertSame([
            'id' => null,
            'status' => null,
            'state' => null,
            'action' => null,
            'text' => null,
            'source' => [
                'type' => null,
                'via' => null,
            ],
            'assignedTo' => null,
            'savedReplyId' => null,
            'to' => [],
            'cc' => [],
            'bcc' => [],
        ], $thread->extract());
    }

    public function testThrowsExceptionWhenProvidingResourceUrl()
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Unrecognized thread type');

        Thread::resourceUrl(123);
    }

    public function testAddAttachment()
    {
        $thread = new Thread();

        $this->assertEmpty($thread->getAttachments());

        $attachment = new Attachment();
        $thread->addAttachment($attachment);
        $this->assertSame($attachment, $thread->getAttachments()->toArray()[0]);
    }
}
