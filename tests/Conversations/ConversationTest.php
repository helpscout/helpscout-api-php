<?php

declare(strict_types=1);

namespace HelpScout\Api\Tests\Conversations;

use DateTime;
use DateTimeInterface;
use HelpScout\Api\Conversations\Conversation;
use HelpScout\Api\Conversations\CustomerWaitingSince;
use HelpScout\Api\Conversations\CustomField;
use HelpScout\Api\Conversations\Threads\ChatThread;
use HelpScout\Api\Customers\Customer;
use HelpScout\Api\Entity\Collection;
use HelpScout\Api\Tags\Tag;
use HelpScout\Api\Users\User;
use PHPUnit\Framework\TestCase;

class ConversationTest extends TestCase
{
    public function testHydrate()
    {
        $conversation = new Conversation();
        $conversation->hydrate([
            'id' => 12,
            'number' => 15473,
            'threads' => 2,
            'type' => 'email',
            'folderId' => 493,
            'status' => 'closed',
            'state' => 'published',
            'subject' => 'Need Help',
            'preview' => "I'm having a hard time resolving this",
            'mailboxId' => 85,
            'assignee' => [
                'id' => 256,
                'first' => 'Mr',
                'last' => 'Robot',
                'email' => 'none@nowhere.com',
            ],
            'createdBy' => [
                'id' => 12,
                'type' => 'customer',
                'email' => 'bear@acme.com',
            ],
            'createdAt' => '2017-04-21T14:39:56Z',
            'closedBy' => 17,
            'closedAt' => '2017-04-21T14:43:24Z',
            'userUpdatedAt' => '2017-04-21T14:43:24Z',
            'customerWaitingSince' => [
                'time' => '2012-07-24T20:18:33Z',
                'friendly' => '20 hours ago',
                'latestReplyFrom' => 'customer',
            ],
            'source' => [
                'type' => 'email',
                'via' => 'customer',
            ],
            'tags' => [
                [
                    'id' => 9150,
                    'color' => '#929499',
                    'tag' => 'vip',
                ],
            ],
            'cc' => [
                'bear@normal.com',
            ],
            'bcc' => [
                'bear@secret.com',
            ],
            'primaryCustomer' => [
                'id' => 238604,
            ],
            'customer' => [
                'id' => 123,
            ],
            'customFields' => [
                [
                    'id' => 6688,
                    'name' => 'Account Type',
                    'value' => '33077',
                ],
            ],
        ]);

        $this->assertSame(12, $conversation->getId());
        $this->assertSame(2, $conversation->getThreadCount());
        $this->assertSame(15473, $conversation->getNumber());
        $this->assertSame('email', $conversation->getType());
        $this->assertSame(493, $conversation->getFolderId());
        $this->assertSame('closed', $conversation->getStatus());
        $this->assertSame('published', $conversation->getState());
        $this->assertSame('Need Help', $conversation->getSubject());
        $this->assertSame("I'm having a hard time resolving this", $conversation->getPreview());
        $this->assertSame(85, $conversation->getMailboxId());

        $assignee = $conversation->getAssignee();
        $this->assertSame(256, $assignee->getId());
        $this->assertSame('Mr', $assignee->getFirstName());
        $this->assertSame('Robot', $assignee->getLastName());
        $this->assertSame('none@nowhere.com', $assignee->getEmail());

        $customer = $conversation->getCreatedByCustomer();
        $this->assertSame(12, $customer->getId());
        $this->assertInstanceOf(DateTimeInterface::class, $conversation->getCreatedAt());
        $this->assertSame('2017-04-21T14:39:56+00:00', $conversation->getCreatedAt()->format('c'));
        $this->assertInstanceOf(DateTimeInterface::class, $conversation->getClosedAt());
        $this->assertSame('2017-04-21T14:43:24+00:00', $conversation->getClosedAt()->format('c'));
        $this->assertInstanceOf(DateTimeInterface::class, $conversation->getUserUpdatedAt());
        $this->assertSame('2017-04-21T14:43:24+00:00', $conversation->getUserUpdatedAt()->format('c'));
        $this->assertSame(17, $conversation->getClosedBy()->getId());

        $waitingSince = $conversation->getCustomerWaitingSince();
        $this->assertSame('2012-07-24T20:18:33+00:00', $waitingSince->getTime()->format('c'));
        $this->assertSame('20 hours ago', $waitingSince->getFriendly());
        $this->assertSame('customer', $waitingSince->getLatestReplyFrom());

        $this->assertSame('email', $conversation->getSourceType());
        $this->assertSame('customer', $conversation->getSourceVia());

        $tag = $conversation->getTags()[0];
        $this->assertSame(9150, $tag->getId());
        $this->assertSame('#929499', $tag->getColor());
        $this->assertSame('vip', $tag->getName());

        $this->assertSame([
            'bear@normal.com',
        ], $conversation->getCC());
        $this->assertSame([
            'bear@secret.com',
        ], $conversation->getBCC());

        $this->assertSame(238604, $conversation->getCustomer()->getId());

        $customField = $conversation->getCustomFields()[0];
        $this->assertSame(6688, $customField->getId());
        $this->assertSame('Account Type', $customField->getName());
        $this->assertSame('33077', $customField->getValue());
    }

    public function testExtractsCreatedByUser()
    {
        $conversation = new Conversation();

        $user = new User();
        $user->setId(9865);
        $conversation->setCreatedByUser($user);

        $extracted = $conversation->extract();

        $this->assertArrayHasKey('createdBy', $extracted);
        $this->assertEquals([
            'id' => 9865,
            'type' => 'user',
        ], $extracted['createdBy']);
    }

    public function testExtract()
    {
        $conversation = new Conversation();
        $conversation->setId(12);
        $conversation->setNumber(3526);
        $conversation->setThreadCount(2);
        $conversation->setAssignTo(2942);
        $conversation->withAutoRepliesEnabled();
        $conversation->setType('email');
        $conversation->setImported(true);
        $conversation->setFolderId(132);
        $conversation->setStatus('closed');
        $conversation->setState('published');
        $conversation->setSubject('Help');
        $conversation->setPreview('Preview');
        $conversation->setMailboxId(13);

        $customer = new Customer();
        $customer->setId(12);
        $conversation->setCreatedByCustomer($customer);

        $conversation->setCreatedAt(new DateTime('2017-04-21T14:39:56Z'));
        $conversation->setClosedAt(new DateTime('2017-04-21T12:23:06Z'));
        $conversation->setUserUpdatedAt(new DateTime('2017-04-21T03:12:06Z'));
        $user = new User();
        $user->setId(14);
        $conversation->setClosedBy($user);
        $conversation->setSourceType('email');
        $conversation->setSourceVia('customer');
        $conversation->setCC([
            'bear@normal.com',
        ]);
        $conversation->setBCC([
            'bear@secret.com',
        ]);

        $customer = new Customer();
        $customer->setId(152);
        $emails = new Collection([
            'mycustomer@domain.com',
        ]);
        $customer->setEmails($emails);
        $conversation->setCustomer($customer);

        $user = new User();
        $user->setId(9865);
        $user->setFirstName('Mr');
        $user->setLastName('Robot');
        $conversation->setAssignee($user);

        $customField = new CustomField();
        $customField->setId(936);
        $customField->setName('Account Type');
        $customField->setValue('Administrator');
        $conversation->setCustomFields(new Collection([
            $customField,
        ]));

        $tag = new Tag();
        $tag->setId('936');
        $tag->setColor('#243513');
        $tag->setName('Productive');
        $conversation->setTags(new Collection([
            $tag,
        ]));

        $customerWaitingSince = new CustomerWaitingSince();
        $customerWaitingSince->setTime(new DateTime('2012-07-24T20:18:33Z'));
        $customerWaitingSince->setFriendly('20 hours ago');
        $customerWaitingSince->setLatestReplyFrom('customer');
        $conversation->setCustomerWaitingSince($customerWaitingSince);

        $this->assertEquals([
            'id' => 12,
            'number' => 3526,
            'threadCount' => 2,
            'autoReply' => true,
            'type' => 'email',
            'assignTo' => 2942,
            'imported' => true,
            'folderId' => 132,
            'status' => 'closed',
            'state' => 'published',
            'subject' => 'Help',
            'preview' => 'Preview',
            'mailboxId' => 13,
            'assignee' => [
                'id' => 9865,
                'firstName' => 'Mr',
                'lastName' => 'Robot',
            ],
            'createdBy' => [
                'id' => 12,
                'type' => 'customer',
            ],
            'createdAt' => '2017-04-21T14:39:56+00:00',
            'closedAt' => '2017-04-21T12:23:06+00:00',
            'closedBy' => 14,
            'userUpdatedAt' => '2017-04-21T03:12:06+00:00',
            'source' => [
                'type' => 'email',
                'via' => 'customer',
            ],
            'cc' => [
                'bear@normal.com',
            ],
            'bcc' => [
                'bear@secret.com',
            ],
            'customer' => [
                'id' => 152,
                'email' => 'mycustomer@domain.com',
            ],
            'customerWaitingSince' => [
                'time' => '2012-07-24T20:18:33+00:00',
                'friendly' => '20 hours ago',
                'latestReplyFrom' => 'customer',
            ],
            'tags' => [
                'Productive',
            ],
            'fields' => [
                [
                    'id' => 936,
                    'name' => 'Account Type',
                    'value' => 'Administrator',
                ],
            ],
        ], $conversation->extract());
    }

    public function testExtractsThreads()
    {
        $conversation = new Conversation();

        $thread = new ChatThread();
        $thread->setId(9865);
        $conversation->setThreads(new Collection([
            $thread,
        ]));

        $extracted = $conversation->extract();

        $this->assertArrayHasKey('threads', $extracted);
        $this->assertEquals(9865, $extracted['threads'][0]['id']);
    }

    public function testExtractNewEntity()
    {
        $conversation = new Conversation();

        $this->assertEquals([
            'id' => null,
            'number' => null,
            'threadCount' => null,
            'autoReply' => false,
            'type' => null,
            'folderId' => null,
            'status' => null,
            'state' => null,
            'subject' => null,
            'preview' => null,
            'mailboxId' => null,
            'assignee' => null,
            'createdAt' => null,
            'closedAt' => null,
            'closedBy' => null,
            'userUpdatedAt' => null,
            'cc' => [],
            'bcc' => [],
            'assignTo' => null,
        ], $conversation->extract());
    }
}
