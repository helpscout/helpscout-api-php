<?php
require __DIR__ . '/../vendor/autoload.php';
require '_credentials.php';

use HelpScout\Api\ApiClientFactory;
use HelpScout\Api\Conversations\Conversation;
use HelpScout\Api\Conversations\ConversationFilters;
use HelpScout\Api\Conversations\ConversationRequest;
use HelpScout\Api\Conversations\CustomField;
use HelpScout\Api\Conversations\Threads\ChatThread;
use HelpScout\Api\Conversations\Threads\PhoneThread;
use HelpScout\Api\Tags\Tag;
use HelpScout\Api\Customers\Customer;
use HelpScout\Api\Entity\Collection;

$client = ApiClientFactory::createClient();
$client = $client->useClientCredentials($appId, $appSecret);

// GET conversation
$conversation = $client->conversations()->get(12);

// GET conversation with the threads
$conversationRequest = new ConversationRequest();
$conversationRequest = $conversationRequest->withThreads();
$conversation = $client->conversations()->get(12, $conversationRequest);

// List conversations
$conversations = $client->conversations()
    ->list()
    ->getFirstPage()
    ->toArray();

$filters = (new ConversationFilters())
    ->inMailbox(1)
    ->inFolder(13)
    ->inStatus('all')
    ->hasTag('testing')
    ->assignedTo(1771)
    ->byNumber(42)
    ->sortField('createdAt')
    ->sortOrder('asc')
    // See https://developer.helpscout.com/mailbox-api/endpoints/conversations/list/#query for details on what you can do with query
    ->withQuery('email:"john@appleseed.com"')
    ->byCustomField(123, 'blue');

$conversations = $client->conversations()->list($filters);

/**
 * Create Conversation: Customer thread - as if the customer emailed in
 */
$customer = new Customer();
$customer->addEmail('my-customer@company.com');

$thread = new \HelpScout\Api\Conversations\Threads\CustomerThread();
$thread->setCustomer($customer);
$thread->setText('Test');

$conversation = new Conversation();
$conversation->setSubject('Testing the PHP SDK v2: Phone Thread');
$conversation->setStatus('active');
$conversation->setType('email');
$conversation->setMailboxId(80261);
$conversation->setCustomer($customer);
$conversation->setThreads(new Collection([
    $thread,
]));
try {
    $conversationId = $client->conversations()->create($conversation);
} catch (\HelpScout\Api\Exception\ValidationErrorException $e) {
    var_dump($e->getError()->getErrors());
}

// Update conversation
$conversationId = 12;
$toMailboxId = 18;
$newCustomerId = 6854;
$assigneeId = 127;
$client->conversations()->move($conversationId, $toMailboxId);
$client->conversations()->updateSubject($conversationId, 'Need more help please');
$client->conversations()->updateCustomer($conversationId, $newCustomerId);
$client->conversations()->publishDraft($conversationId);
$client->conversations()->updateStatus($conversationId, 'closed');
$client->conversations()->assign($conversationId, $assigneeId);
$client->conversations()->unassign($conversationId);
$conversationId = 662118787;

// Update custom fields on a conversation
$customField = new CustomField();
$customField->setId(10524);
$customField->setValue(new DateTime('today'));
$client->conversations()->updateCustomFields($conversationId, [$customField]);

// Update tags on a conversation.  Can either use a tag name or a Tag
$client->conversations()->updateTags($conversationId, [
    'Annual',
    'self-signup-lead'
]);
