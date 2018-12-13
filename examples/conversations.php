<?php
require __DIR__ . '/../vendor/autoload.php';
require '_credentials.php';

use HelpScout\Api\ApiClientFactory;
use HelpScout\Api\Conversations\Conversation;
use HelpScout\Api\Conversations\CustomField;
use HelpScout\Api\Conversations\Threads\ChatThread;
use HelpScout\Api\Customers\Customer;
use HelpScout\Api\Entity\Collection;
use HelpScout\Api\Tags\Tag;

$client = ApiClientFactory::createClient();
$client = $client->useClientCredentials($appId, $appSecret);

// GET conversation
$conversation = $client->getConversation(12);

// List conversations
$conversations = $client->getConversations()
    ->getFirstPage()
    ->toArray();

// Create conversation
$noteCustomer = new Customer();
$noteCustomer->setId(163315601);
$thread = new ChatThread();
$thread->setCustomer($noteCustomer);
$thread->setText('Test');

$conversation = new Conversation();
$conversation->setSubject('Testing the PHP SDK v2');
$conversation->setStatus('active');
$conversation->setType('email');
$conversation->setAssignTo(271315);
$conversation->setMailboxId(138367);
$conversation->setCustomer($noteCustomer);
$conversation->setThreads(new Collection([
    $thread,
]));

$client->createConversation($conversation);

// Update conversation
$conversationId = 12;
$client->moveConversation($conversationId, 18);
$client->updateConversationSubject($conversationId, 'Need more help please');
$client->updateConversationCustomer($conversationId, 6854);
$client->publishConversationDraft($conversationId);
$client->updateConversationStatus($conversationId, 'closed');
$client->assignConversation($conversationId, 127);
$client->unassignConversation($conversationId);
$conversationId = 662118787;

// Update custom fields on a conversation
$customField = new CustomField();
$customField->setId(10524);
$customField->setValue(new DateTime('today'));
$client->updateConversationCustomFields($conversationId, [$customField]);

// Update tags on a conversation.  Can either use a tag name or a Tag
$client->updateConversationTags($conversationId, [
    'Annual',
    'self-signup-lead'
]);