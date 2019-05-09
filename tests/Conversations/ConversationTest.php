<?php

declare(strict_types=1);

namespace HelpScout\Api\Tests\Conversations;

use DateTime;
use DateTimeInterface;
use HelpScout\Api\Conversations\ChatConversation;
use HelpScout\Api\Conversations\Conversation;
use HelpScout\Api\Conversations\CustomerWaitingSince;
use HelpScout\Api\Conversations\CustomField;
use HelpScout\Api\Conversations\EmailConversation;
use HelpScout\Api\Conversations\PhoneConversation;
use HelpScout\Api\Conversations\Status;
use HelpScout\Api\Conversations\Threads\ChatThread;
use HelpScout\Api\Conversations\Threads\Thread;
use HelpScout\Api\Customers\Customer;
use HelpScout\Api\Customers\Entry\Email;
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
        $this->assertSame(Status::CLOSED, $conversation->getStatus());
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
        $conversation->setAssignTo(9865);
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

        $email = new Email();
        $email->setValue('mycustomer@domain.com');
        $customer = new Customer();
        $customer->setId(152);
        $emails = new Collection([$email]);
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

        $this->assertArraySubset([
            'id' => 12,
            'number' => 3526,
            'threadCount' => 2,
            'autoReply' => true,
            'type' => 'email',
            'assignTo' => 9865,
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
            'createdAt' => '2017-04-21T14:39:56Z',
            'closedAt' => '2017-04-21T12:23:06Z',
            'closedBy' => 14,
            'userUpdatedAt' => '2017-04-21T03:12:06Z',
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
                'time' => '2012-07-24T20:18:33Z',
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

    /**
     * See https://github.com/helpscout/helpscout-api-php/issues/111.
     */
    public function testSettingAssigneeAlsoSetsAssignToId()
    {
        $convo = new Conversation();

        $assignee = new User();
        $assignee->setId(41);
        $convo->setAssignee($assignee);

        $this->assertSame($assignee->getId(), $convo->getAssignTo());
    }

    public function testChatConvo()
    {
        $convo = new ChatConversation();
        $this->assertSame(Conversation::TYPE_CHAT, $convo->getType());
        $this->assertTrue($convo->isChatConvo());
    }

    public function testEmailConvo()
    {
        $convo = new EmailConversation();
        $this->assertSame(Conversation::TYPE_EMAIL, $convo->getType());
        $this->assertTrue($convo->isEmailConvo());
    }

    public function testPhoneConvo()
    {
        $convo = new PhoneConversation();
        $this->assertSame(Conversation::TYPE_PHONE, $convo->getType());
        $this->assertTrue($convo->isPhoneConvo());
    }

    public function testStatusMethods()
    {
        $convo = new Conversation();
        $this->assertNull($convo->getStatus());

        $convo->setActive();
        $this->assertTrue($convo->isActive());

        $convo->setPending();
        $this->assertTrue($convo->isPending());

        $convo->setSpam();
        $this->assertTrue($convo->isSpam());

        $convo->setClosed();
        $this->assertTrue($convo->isClosed());

        $this->assertNull($convo->getAssignee());
        $this->assertFalse($convo->isAssigned());

        $user = new User();
        $user->setId(1);
        $convo->assignTo($user);
        $this->assertTrue($convo->isAssigned());

        $convo->publish();
        $this->assertTrue($convo->isPublished());

        $convo->makeDraft();
        $this->assertTrue($convo->isDraft());

        $convo->delete();
        $this->assertTrue($convo->isDeleted());
    }

    public function testAddMethods()
    {
        $convo = new Conversation();

        $tag = new Tag();
        $this->assertEmpty($convo->getTags()->toArray());
        $convo->addTag($tag);
        $this->assertSame($tag, $convo->getTags()->toArray()[0]);

        $field = new CustomField();
        $this->assertEmpty($convo->getCustomFields()->toArray());
        $convo->addCustomField($field);
        $this->assertSame($field, $convo->getCustomFields()->toArray()[0]);

        $thread = new Thread();
        $this->assertEmpty($convo->getThreads()->toArray());
        $convo->addThread($thread);
        $this->assertSame($thread, $convo->getThreads()->toArray()[0]);
    }

    public function testHydratesConversationFromWebhookRequest()
    {
        $json = <<<EOF
{
    "number": 123,
    "id": 4934923493,
    "folderId": 14932,
    "type": "email",
    "isDraft": false,
    "owner": null,
    "mailbox": {
        "id": 12334,
        "name": "Sales"
    },
    "customer": {
        "id": 179783313,
        "firstName": "John",
        "lastName": "Smith",
        "email": "john@ourcompany.com",
        "type": "customer"
    },
    "threadCount": 3,
    "status": "active",
    "subject": "Re: Following up on your Demo Request",
    "preview": "Are you still interested in a demo?",
    "createdBy": {
        "id": 179783313,
        "firstName": "John",
        "lastName": "Smith",
        "email": "john@ourcompany.com",
        "type": "customer"
    },
    "createdAt": "2019-02-28T15:41:12Z",
    "modifiedAt": "2019-04-15T20:15:32Z",
    "closedAt": null,
    "closedBy": null,
    "source": {
        "type": "email",
        "via": "customer"
    },
    "cc": [
        "customer-address@gmail.com"
    ],
    "bcc": null,
    "tags": [
        "new-customer"
    ],
    "threads": [
    {
        "id": 2198262392,
        "assignedTo": null,
        "status": "active",
        "createdAt": "2019-02-28T15:48:20Z",
        "createdBy": {
            "id": 179783313,
            "firstName": "John",
            "lastName": "Smith",
            "email": "john@ourcompany.com",
            "type": "customer"
        },
        "source": {
            "type": "email",
            "via": "customer"
        },
        "actionType": null,
        "actionSourceId": 0,
        "fromMailbox": null,
        "type": "customer",
        "state": "published",
        "customer": {
            "id": 179783313,
            "firstName": "Casey",
            "lastName": "Lockwood",
            "email": "casey@helpscout.com",
            "type": "customer"
        },
        "body": "Are you still interested in a demo?",
        "to": [
            "customer-address@gmail.com"
        ],
        "cc": null,
        "bcc": null,
        "attachments": null
    }
    ],
    "customFields": []
}
EOF;

        $json = json_decode($json, true);
        $conversation = new Conversation();
        $conversation->hydrate($json);

        $this->assertSame(4934923493, $conversation->getId());
        $this->assertSame(3, $conversation->getThreadCount());
        $this->assertSame(123, $conversation->getNumber());
        $this->assertSame('email', $conversation->getType());
        $this->assertSame(14932, $conversation->getFolderId());
        $this->assertSame(Status::ACTIVE, $conversation->getStatus());
        $this->assertSame('Re: Following up on your Demo Request', $conversation->getSubject());
        $this->assertSame('Are you still interested in a demo?', $conversation->getPreview());
        $this->assertSame(12334, $conversation->getMailboxId());
        $this->assertSame(12334, $conversation->getMailbox()->getId());

        $customer = $conversation->getCreatedByCustomer();
        $this->assertSame(179783313, $customer->getId());

        $this->assertSame('email', $conversation->getSourceType());
        $this->assertSame('customer', $conversation->getSourceVia());

        $this->assertSame([
            'customer-address@gmail.com',
        ], $conversation->getCC());

        $this->assertSame(179783313, $conversation->getCustomer()->getId());

        $thread = $conversation->getThreads()[0];
        $this->assertSame(2198262392, $thread->getId());

        $tag = $conversation->getTags()[0];
        $this->assertSame('new-customer', $tag->getName());
    }
}
