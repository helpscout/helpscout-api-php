<?php
include_once 'ApiClient.php';

use HelpScout\ApiClient;

$client = ApiClient::getInstance();
$client->setKey('example-key');


// The customer associated with the conversation
$customerRef = $client->getCustomerRefProxy(null, 'customer@example.com');

$conversation = new \HelpScout\model\Conversation();
$conversation->setType     ('email');
$conversation->setSubject  ('I need help');
$conversation->setCustomer ($customerRef);
$conversation->setCreatedBy($customerRef);

// The mailbox associated with the conversation
$conversation->setMailbox  ($client->getMailboxProxy(2431));

// A conversation must have at least one thread
$thread = new \HelpScout\model\thread\Customer();
$thread->setBody('Hello there - I need some help please.');

// Create by: required
$thread->setCreatedBy($customerRef);

$conversation->addLineItem($thread);

$client->createConversation($conversation);

echo $conversation->getId();

// if the customer id is important to you (for the customer created above),
// grab the newly created convo
$conversation = $client->getConversation($conversation->getId());

$customerId = $conversation->getCreatedBy()->getId();
