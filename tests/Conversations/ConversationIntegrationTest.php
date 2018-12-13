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
        $this->client->conversations()->delete(1);

        $this->verifySingleRequest(
            'https://api.helpscout.net/v2/conversations/1',
            'DELETE'
        );
    }

    public function testGetConversation()
    {
        $this->stubResponse(200, ConversationPayloads::getConversation(1));

        $conversation = $this->client->conversations()->get(1);

        $this->assertInstanceOf(Conversation::class, $conversation);
        $this->assertSame(1, $conversation->getId());

        $this->verifySingleRequest(
            'https://api.helpscout.net/v2/conversations/1'
        );
    }

    public function testCreateConversation()
    {
        $this->stubResponse(200, ConversationPayloads::getConversation(1));

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
        $this->stubResponse(200, ConversationPayloads::getConversation(1));
        $this->stubResponse(200, UserPayloads::getUser(256));

        $request = (new ConversationRequest())
            ->withAssignee();

        $conversation = $this->client->conversations()->get(1, $request);
        $assignee = $conversation->getAssignee();

        $this->assertInstanceOf(User::class, $assignee);

        $this->verifyMultpleRequests([
            ['GET', 'https://api.helpscout.net/v2/conversations/1'],
            ['GET', 'https://api.helpscout.net/v2/users/256'],
        ]);
    }

    public function testGetConversationPreloadsClosedBy()
    {
        $this->stubResponse(200, ConversationPayloads::getConversation(1));
        $this->stubResponse(200, UserPayloads::getUser(17));

        $request = (new ConversationRequest())
            ->withClosedBy();

        $conversation = $this->client->conversations()->get(1, $request);
        $closedBy = $conversation->getClosedBy();

        $this->assertInstanceOf(User::class, $closedBy);

        $this->verifyMultpleRequests([
            ['GET', 'https://api.helpscout.net/v2/conversations/1'],
            ['GET', 'https://api.helpscout.net/v2/users/17'],
        ]);
    }

    public function testGetConversationPreloadsCreatedByCustomer()
    {
        $this->stubResponse(200, ConversationPayloads::getConversation(1));
        $this->stubResponse(200, CustomerPayloads::getCustomer(12));

        $request = (new ConversationRequest())
            ->withCreatedByCustomer();

        $conversation = $this->client->conversations()->get(1, $request);
        $createdByCustomer = $conversation->getCreatedByCustomer();

        $this->assertInstanceOf(Customer::class, $createdByCustomer);

        $this->verifyMultpleRequests([
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

        $this->stubResponse(200, json_encode($conversationPayload));
        $this->stubResponse(200, UserPayloads::getUser(17));

        $request = (new ConversationRequest())
            ->withCreatedByUser();

        $conversation = $this->client->conversations()->get(1, $request);
        $createdByUser = $conversation->getCreatedByUser();

        $this->assertInstanceOf(User::class, $createdByUser);

        $this->verifyMultpleRequests([
            ['GET', 'https://api.helpscout.net/v2/conversations/1'],
            ['GET', 'https://api.helpscout.net/v2/users/17'],
        ]);
    }

    public function testGetConversationPreloadsMailboxes()
    {
        $this->stubResponse(200, ConversationPayloads::getConversation(1));
        $this->stubResponse(200, MailboxPayloads::getMailbox(1));

        $request = (new ConversationRequest())
            ->withMailbox();

        $conversation = $this->client->conversations()->get(1, $request);
        $mailbox = $conversation->getMailbox();

        $this->assertInstanceOf(Mailbox::class, $mailbox);

        $this->verifyMultpleRequests([
            ['GET', 'https://api.helpscout.net/v2/conversations/1'],
            ['GET', 'https://api.helpscout.net/v2/mailboxes/85'],
        ]);
    }

    public function testGetConversationPreloadsPrimaryCustomer()
    {
        $this->stubResponse(200, ConversationPayloads::getConversation(1));
        $this->stubResponse(200, CustomerPayloads::getCustomer(238604));

        $request = (new ConversationRequest())
            ->withPrimaryCustomer();

        $conversation = $this->client->conversations()->get(1, $request);
        $customer = $conversation->getCustomer();
        $this->assertInstanceOf(Customer::class, $customer);

        $this->verifyMultpleRequests([
            ['GET', 'https://api.helpscout.net/v2/conversations/1'],
            ['GET', 'https://api.helpscout.net/v2/customers/238604'],
        ]);
    }

    public function testGetConversationPreloadsThreads()
    {
        $this->stubResponse(200, ConversationPayloads::getConversation(1));
        $this->stubResponse(200, ThreadPayloads::getThreads(1, 5));

        $request = (new ConversationRequest())
            ->withThreads();

        $conversation = $this->client->conversations()->get(1, $request);
        $threads = $conversation->getThreads();

        $this->assertInstanceOf(Collection::class, $threads);
        $this->assertInstanceOf(Thread::class, $threads[0]);

        $this->verifyMultpleRequests([
            ['GET', 'https://api.helpscout.net/v2/conversations/1'],
            ['GET', 'https://api.helpscout.net/v2/conversations/1/threads'],
        ]);
    }

    public function testListConversations()
    {
        $this->stubResponse(200, ConversationPayloads::getConversations(1, 10));

        $conversations = $this->client->conversations()->list();

        $this->assertCount(10, $conversations);
        $this->assertInstanceOf(Conversation::class, $conversations[0]);

        $this->verifySingleRequest(
            'https://api.helpscout.net/v2/conversations'
        );
    }

    public function testListConversationsWithFilters()
    {
        $this->stubResponse(200, ConversationPayloads::getConversations(1, 10));

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
        $this->stubResponse(200, ConversationPayloads::getConversations(1, 0));

        $conversations = $this->client->conversations()->list();

        $this->assertCount(0, $conversations);

        $this->verifySingleRequest(
            'https://api.helpscout.net/v2/conversations'
        );
    }

    public function testGetConversationsParsesPageMetadata()
    {
        $this->stubResponse(200, ConversationPayloads::getConversations(3, 35));

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
        $this->stubResponse(200, ConversationPayloads::getConversations(1, $totalElements));
        $this->stubResponse(200, ConversationPayloads::getConversations(2, $totalElements));

        $conversations = $this->client->conversations()->list()->getPage(2);

        $this->assertCount(10, $conversations);
        $this->assertInstanceOf(Conversation::class, $conversations[0]);

        $requests = [
            ['GET', 'https://api.helpscout.net/v2/conversations'],
            ['GET', 'https://api.helpscout.net/v2/conversations?page=2'],
        ];
        $this->verifyMultpleRequests($requests);
    }

    public function testCanMoveConversations()
    {
        $this->client->conversations()->move(1, 43);

        $this->verifyRequestWithData('https://api.helpscout.net/v2/conversations/1', 'PATCH', [
            'op' => 'move',
            'path' => '/mailboxId',
            'value' => 43,
        ]);
    }

    public function testCanUpdateSubject()
    {
        $this->client->conversations()->updateSubject(1, 'Help me');

        $this->verifyRequestWithData('https://api.helpscout.net/v2/conversations/1', 'PATCH', [
            'op' => 'replace',
            'path' => '/subject',
            'value' => 'Help me',
        ]);
    }

    public function testCanUpdateCustomer()
    {
        $this->client->conversations()->updateCustomer(1, 13);

        $this->verifyRequestWithData('https://api.helpscout.net/v2/conversations/1', 'PATCH', [
            'op' => 'replace',
            'path' => '/primaryCustomer.id',
            'value' => 13,
        ]);
    }

    public function testCanPublishDraft()
    {
        $this->client->conversations()->publishDraft(1);

        $this->verifyRequestWithData('https://api.helpscout.net/v2/conversations/1', 'PATCH', [
            'op' => 'replace',
            'path' => '/draft',
            'value' => true,
        ]);
    }

    public function testCanUpdateConversationStatus()
    {
        $this->client->conversations()->updateStatus(1, 'closed');

        $this->verifyRequestWithData('https://api.helpscout.net/v2/conversations/1', 'PATCH', [
            'op' => 'replace',
            'path' => '/status',
            'value' => 'closed',
        ]);
    }

    public function testCanAssignConversation()
    {
        $this->client->conversations()->assign(1, 9835);

        $this->verifyRequestWithData('https://api.helpscout.net/v2/conversations/1', 'PATCH', [
            'op' => 'replace',
            'path' => '/assignTo',
            'value' => 9835,
        ]);
    }

    public function testCanUnassignConversation()
    {
        $this->client->conversations()->unassign(1);

        $this->verifyRequestWithData('https://api.helpscout.net/v2/conversations/1', 'PATCH', [
            'op' => 'remove',
            'path' => '/assignTo',
        ]);
    }

    public function testUpdatesCustomFields()
    {
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

    public function testUpdatesTags()
    {
        $this->client->conversations()->updateTags(14, ['Support']);

        $tags = new TagsCollection();
        $tags->setTags(['Support']);

        $this->verifyRequestWithData(
            'https://api.helpscout.net/v2/conversations/14/tags',
            'PUT',
            $tags->extract()
        );
    }
}
