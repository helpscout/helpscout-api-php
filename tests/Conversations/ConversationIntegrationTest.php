<?php

declare(strict_types=1);

namespace HelpScout\Api\Tests\Conversations;

use HelpScout\Api\Conversations\Conversation;
use HelpScout\Api\Conversations\ConversationFilters;
use HelpScout\Api\Conversations\ConversationRequest;
use HelpScout\Api\Conversations\CustomField;
use HelpScout\Api\Conversations\CustomFieldsCollection;
use HelpScout\Api\Conversations\Threads\Thread;
use HelpScout\Api\Customers\Customer;
use HelpScout\Api\Entity\Collection;
use HelpScout\Api\Mailboxes\Mailbox;
use HelpScout\Api\Tags\Tag;
use HelpScout\Api\Tags\TagsCollection;
use HelpScout\Api\Tests\ApiClientIntegrationTestCase;
use HelpScout\Api\Tests\Payloads\ConversationPayloads;
use HelpScout\Api\Tests\Payloads\CustomerPayloads;
use HelpScout\Api\Tests\Payloads\MailboxPayloads;
use HelpScout\Api\Tests\Payloads\ThreadPayloads;
use HelpScout\Api\Tests\Payloads\UserPayloads;
use HelpScout\Api\Users\User;

/**
 * @group integration
 */
class ConversationIntegrationTest extends ApiClientIntegrationTestCase
{
    public function testDeleteConversation()
    {
        $this->stubResponse($this->getResponse(201));
        $this->client->conversations()->delete(1);

        $this->verifySingleRequest(
            'https://api.helpscout.net/v2/conversations/1',
            'DELETE'
        );
    }

    public function testGetConversation()
    {
        $this->stubResponse(
            $this->getResponse(200, ConversationPayloads::getConversation(1))
        );

        $conversation = $this->client->conversations()->get(1);

        $this->assertInstanceOf(Conversation::class, $conversation);
        $this->assertSame(1, $conversation->getId());

        $this->verifySingleRequest(
            'https://api.helpscout.net/v2/conversations/1'
        );
    }

    public function testCreateConversation()
    {
        $this->stubResponse($this->getResponse(200, ConversationPayloads::getConversation(1)));

        $conversation = new Conversation();
        $conversation->hydrate([
            'subject' => 'Help please',
            'type' => 'email',
        ]);

        $this->client->conversations()->create($conversation);

        $this->verifyRequestWithData(
            'https://api.helpscout.net/v2/conversations',
            'POST',
            $conversation->extract()
        );
    }

    public function testGetConversationPreloadsAssignee()
    {
        $this->stubResponses([
            $this->getResponse(200, ConversationPayloads::getConversation(1)),
            $this->getResponse(200, UserPayloads::getUser(256)),
        ]);

        $request = (new ConversationRequest())
            ->withAssignee();

        $conversation = $this->client->conversations()->get(1, $request);
        $assignee = $conversation->getAssignee();

        $this->assertInstanceOf(User::class, $assignee);

        $this->verifyMultipleRequests([
            ['GET', 'https://api.helpscout.net/v2/conversations/1'],
            ['GET', 'https://api.helpscout.net/v2/users/256'],
        ]);
    }

    public function testGetConversationPreloadsClosedBy()
    {
        $this->stubResponses([
            $this->getResponse(200, ConversationPayloads::getConversation(1)),
            $this->getResponse(200, UserPayloads::getUser(17)),
        ]);

        $request = (new ConversationRequest())
            ->withClosedBy();

        $conversation = $this->client->conversations()->get(1, $request);
        $closedBy = $conversation->getClosedBy();

        $this->assertInstanceOf(User::class, $closedBy);

        $this->verifyMultipleRequests([
            ['GET', 'https://api.helpscout.net/v2/conversations/1'],
            ['GET', 'https://api.helpscout.net/v2/users/17'],
        ]);
    }

    public function testGetConversationPreloadsCreatedByCustomer()
    {
        $this->stubResponses([
            $this->getResponse(200, ConversationPayloads::getConversation(1)),
            $this->getResponse(200, CustomerPayloads::getCustomer(12)),
        ]);

        $request = (new ConversationRequest())
            ->withCreatedByCustomer();

        $conversation = $this->client->conversations()->get(1, $request);
        $createdByCustomer = $conversation->getCreatedByCustomer();

        $this->assertInstanceOf(Customer::class, $createdByCustomer);

        $this->verifyMultipleRequests([
            ['GET', 'https://api.helpscout.net/v2/conversations/1'],
            ['GET', 'https://api.helpscout.net/v2/customers/12'],
        ]);
    }

    public function testGetConversationPreloadsCreatedByUser()
    {
        $conversationPayload = json_decode(ConversationPayloads::getConversation(1), true);

        // modify this stub so that it looks like it was created by a user instead of a customer
        $conversationPayload['createdBy']['type'] = 'user';
        $conversationPayload['_links']['createdByUser'] = [
            'href' => 'https://api.helpscout.net/v2/users/17',
        ];

        $this->stubResponses([
            $this->getResponse(200, json_encode($conversationPayload)),
            $this->getResponse(200, UserPayloads::getUser(17)),
        ]);

        $request = (new ConversationRequest())
            ->withCreatedByUser();

        $conversation = $this->client->conversations()->get(1, $request);
        $createdByUser = $conversation->getCreatedByUser();

        $this->assertInstanceOf(User::class, $createdByUser);

        $this->verifyMultipleRequests([
            ['GET', 'https://api.helpscout.net/v2/conversations/1'],
            ['GET', 'https://api.helpscout.net/v2/users/17'],
        ]);
    }

    public function testGetConversationPreloadsMailboxes()
    {
        $this->stubResponses([
            $this->getResponse(200, ConversationPayloads::getConversation(1)),
            $this->getResponse(200, MailboxPayloads::getMailbox(1)),
        ]);

        $request = (new ConversationRequest())
            ->withMailbox();

        $conversation = $this->client->conversations()->get(1, $request);
        $mailbox = $conversation->getMailbox();

        $this->assertInstanceOf(Mailbox::class, $mailbox);

        $this->verifyMultipleRequests([
            ['GET', 'https://api.helpscout.net/v2/conversations/1'],
            ['GET', 'https://api.helpscout.net/v2/mailboxes/85'],
        ]);
    }

    public function testGetConversationPreloadsPrimaryCustomer()
    {
        $this->stubResponses([
            $this->getResponse(200, ConversationPayloads::getConversation(1)),
            $this->getResponse(200, CustomerPayloads::getCustomer(238604)),
        ]);

        $request = (new ConversationRequest())
            ->withPrimaryCustomer();

        $conversation = $this->client->conversations()->get(1, $request);
        $customer = $conversation->getCustomer();
        $this->assertInstanceOf(Customer::class, $customer);

        $this->verifyMultipleRequests([
            ['GET', 'https://api.helpscout.net/v2/conversations/1'],
            ['GET', 'https://api.helpscout.net/v2/customers/238604'],
        ]);
    }

    public function testGetConversationPreloadsThreads()
    {
        $this->stubResponses([
            $this->getResponse(200, ConversationPayloads::getConversation(1)),
            $this->getResponse(200, ThreadPayloads::getThreads(1, 5)),
        ]);

        $request = (new ConversationRequest())
            ->withThreads();

        $conversation = $this->client->conversations()->get(1, $request);
        $threads = $conversation->getThreads();

        $this->assertInstanceOf(Collection::class, $threads);
        $this->assertInstanceOf(Thread::class, $threads[0]);

        $this->verifyMultipleRequests([
            ['GET', 'https://api.helpscout.net/v2/conversations/1'],
            ['GET', 'https://api.helpscout.net/v2/conversations/1/threads'],
        ]);
    }

    public function testListConversations()
    {
        $this->stubResponse(
            $this->getResponse(200, ConversationPayloads::getConversations(1, 10))
        );

        $conversations = $this->client->conversations()->list();

        $this->assertCount(10, $conversations);
        $this->assertInstanceOf(Conversation::class, $conversations[0]);

        $this->verifySingleRequest(
            'https://api.helpscout.net/v2/conversations'
        );
    }

    public function testListConversationsWithFilters()
    {
        $this->stubResponse(
            $this->getResponse(200, ConversationPayloads::getConversations(1, 10))
        );

        $filters = (new ConversationFilters())
            ->withAssignedTo(256);

        $conversations = $this->client->conversations()->list($filters);

        $this->assertCount(10, $conversations);
        $this->assertInstanceOf(Conversation::class, $conversations[0]);

        $this->verifySingleRequest(
            'https://api.helpscout.net/v2/conversations?assigned_to=256'
        );
    }

    public function testGetConversationsWithEmptyCollection()
    {
        $this->stubResponse(
            $this->getResponse(200, ConversationPayloads::getConversations(1, 0))
        );

        $conversations = $this->client->conversations()->list();

        $this->assertCount(0, $conversations);

        $this->verifySingleRequest(
            'https://api.helpscout.net/v2/conversations'
        );
    }

    public function testGetConversationsParsesPageMetadata()
    {
        $this->stubResponse($this->getResponse(200, ConversationPayloads::getConversations(3, 35)));

        $conversations = $this->client->conversations()->list();

        $this->assertSame(3, $conversations->getPageNumber());
        $this->assertSame(10, $conversations->getPageSize());
        $this->assertSame(10, $conversations->getPageElementCount());
        $this->assertSame(35, $conversations->getTotalElementCount());
        $this->assertSame(4, $conversations->getTotalPageCount());
    }

    public function testGetConversationsLazyLoadsPages()
    {
        $totalElements = 20;
        $this->stubResponses([
            $this->getResponse(200, ConversationPayloads::getConversations(1, $totalElements)),
            $this->getResponse(200, ConversationPayloads::getConversations(2, $totalElements)),
        ]);

        $conversations = $this->client->conversations()->list()->getPage(2);

        $this->assertCount(10, $conversations);
        $this->assertInstanceOf(Conversation::class, $conversations[0]);

        $requests = [
            ['GET', 'https://api.helpscout.net/v2/conversations'],
            ['GET', 'https://api.helpscout.net/v2/conversations?page=2'],
        ];
        $this->verifyMultipleRequests($requests);
    }

    public function testCanMoveConversations()
    {
        $this->stubResponse(
            $this->getResponse(204)
        );
        $this->client->conversations()->move(1, 43);

        $this->verifyRequestWithData('https://api.helpscout.net/v2/conversations/1', 'PATCH', [
            'op' => 'move',
            'path' => '/mailboxId',
            'value' => 43,
        ]);
    }

    public function testCanUpdateSubject()
    {
        $this->stubResponse(
            $this->getResponse(204)
        );
        $this->client->conversations()->updateSubject(1, 'Help me');

        $this->verifyRequestWithData('https://api.helpscout.net/v2/conversations/1', 'PATCH', [
            'op' => 'replace',
            'path' => '/subject',
            'value' => 'Help me',
        ]);
    }

    public function testCanUpdateCustomer()
    {
        $this->stubResponse(
            $this->getResponse(204)
        );
        $this->client->conversations()->updateCustomer(1, 13);

        $this->verifyRequestWithData('https://api.helpscout.net/v2/conversations/1', 'PATCH', [
            'op' => 'replace',
            'path' => '/primaryCustomer.id',
            'value' => 13,
        ]);
    }

    public function testCanPublishDraft()
    {
        $this->stubResponse(
            $this->getResponse(204)
        );
        $this->client->conversations()->publishDraft(1);

        $this->verifyRequestWithData('https://api.helpscout.net/v2/conversations/1', 'PATCH', [
            'op' => 'replace',
            'path' => '/draft',
            'value' => true,
        ]);
    }

    public function testCanUpdateConversationStatus()
    {
        $this->stubResponse(
            $this->getResponse(204)
        );
        $this->client->conversations()->updateStatus(1, 'closed');

        $this->verifyRequestWithData('https://api.helpscout.net/v2/conversations/1', 'PATCH', [
            'op' => 'replace',
            'path' => '/status',
            'value' => 'closed',
        ]);
    }

    public function testCanAssignConversation()
    {
        $this->stubResponse(
            $this->getResponse(204)
        );
        $this->client->conversations()->assign(1, 9835);

        $this->verifyRequestWithData('https://api.helpscout.net/v2/conversations/1', 'PATCH', [
            'op' => 'replace',
            'path' => '/assignTo',
            'value' => 9835,
        ]);
    }

    public function testCanUnassignConversation()
    {
        $this->stubResponse(
            $this->getResponse(204)
        );
        $this->client->conversations()->unassign(1);

        $this->verifyRequestWithData('https://api.helpscout.net/v2/conversations/1', 'PATCH', [
            'op' => 'remove',
            'path' => '/assignTo',
        ]);
    }

    public function testUpdatesCustomFields()
    {
        $this->stubResponse(
            $this->getResponse(204)
        );

        $customField = new CustomField();
        $customField->setId(10524);
        $customField->setValue(new \DateTime('today'));

        $this->client->conversations()->updateCustomFields(12, [$customField]);

        $fields = new CustomFieldsCollection();
        $fields->setCustomFields([$customField]);

        $this->verifyRequestWithData(
            'https://api.helpscout.net/v2/conversations/12/fields',
            'PUT',
            $fields->extract()
        );
    }

    public function testUpdatesCustomFieldsWithEntityCollectionOfCustomFields()
    {
        $this->stubResponse(
            $this->getResponse(204)
        );

        $customField = new CustomField();
        $customField->setId(10524);
        $customField->setValue(new \DateTime('today'));

        $customFields = new Collection([$customField]);

        $this->client->conversations()->updateCustomFields(12, $customFields);

        $fields = new CustomFieldsCollection();

        $fields->setCustomFields($customFields->toArray());

        $this->verifyRequestWithData(
            'https://api.helpscout.net/v2/conversations/12/fields',
            'PUT',
            $fields->extract()
        );
    }

    public function testUpdatesCustomFieldsWithCustomFieldsCollection()
    {
        $this->stubResponse(
            $this->getResponse(204)
        );

        $customField = new CustomField();
        $customField->setId(10524);
        $customField->setValue(new \DateTime('today'));

        $customFieldsCollection = new CustomFieldsCollection();
        $customFieldsCollection->setCustomFields([$customField]);

        $this->client->conversations()->updateCustomFields(14, $customFieldsCollection);

        $this->verifyRequestWithData(
            'https://api.helpscout.net/v2/conversations/14/fields',
            'PUT',
            $customFieldsCollection->extract()
        );
    }

    public function testUpdatesTagsWithArrayOfTagNames()
    {
        $this->stubResponse(
            $this->getResponse(204)
        );
        $this->client->conversations()->updateTags(14, ['Support']);

        $tags = new TagsCollection();
        $tags->setTags(['Support']);

        $this->verifyRequestWithData(
            'https://api.helpscout.net/v2/conversations/14/tags',
            'PUT',
            $tags->extract()
        );
    }

    public function testUpdatesTagsWithTagCollection()
    {
        $this->stubResponse(
            $this->getResponse(204)
        );

        $tags = new TagsCollection();
        $tags->setTags(['Support']);

        $this->client->conversations()->updateTags(14, $tags);

        $this->verifyRequestWithData(
            'https://api.helpscout.net/v2/conversations/14/tags',
            'PUT',
            $tags->extract()
        );
    }

    public function testUpdatesTagsWithEntityCollectionOfTags()
    {
        $this->stubResponse(
            $this->getResponse(204)
        );

        $tag = new Tag();
        $tag->setName('Support');
        $tags = new Collection([$tag]);

        $this->client->conversations()->updateTags(14, $tags);

        $this->verifyRequestWithData(
            'https://api.helpscout.net/v2/conversations/14/tags',
            'PUT',
            [
                'tags' => [
                    'Support',
                ],
            ]
        );
    }
}
