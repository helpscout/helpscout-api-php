<?php

use HelpScout\ApiClient;

$client = ApiClient::getInstance();
$client->setKey('example-key');

// The mailbox associated with the conversation
$mailbox = new \HelpScout\model\ref\MailboxRef();
$mailbox->setId(123);

// The customer associated with the conversation
$customer = new \HelpScout\model\ref\CustomerRef();
$customer->setId(12345);
$customer->setEmail("customer@example.com");

$conversation = new \HelpScout\model\Conversation();
$conversation->setSubject("I need help!");
$conversation->setMailbox($mailbox);
$conversation->setCustomer($customer);
$conversation->setType("email");

// A conversation must have at least one thread
$thread = new \HelpScout\model\thread\Customer();
$thread->setType("customer");
$thread->setBody("Hello. I need some help.");
$thread->setStatus("active");

// Create by: required
$createdBy = new \HelpScout\model\ref\PersonRef();
$createdBy->setId(12345);
$createdBy->setType("customer");
$thread->setCreatedBy($createdBy);

// Assigned to: not required - defaults to 'anyone'
$assignedTo = new \HelpScout\model\ref\PersonRef();
$assignedTo->setId(100);
$assignedTo->setType("user");
$thread->setAssignedTo($assignedTo);

// Cc and Bcc
$thread->setCcList(array("foo@example.com", "bar@example.com"));
$thread->setBccList(array("foobar@example.com", "barfoo@example.com"));

// Attachments: attachments must be sent to the API before they can
// be used when creating a thread. Use the hash value returned when
// creating the attachment to associate it with a ticket.
$attachment = new \HelpScout\model\Attachment();
$attachment->setHash("j894hg93gh9egh934gh34g8hjhvbdjvhbweg3");
$thread->setAttachments(array($attachment));

$conversation->setThreads(array($thread));
$conversation->setCreatedBy($createdBy);

$client->createConversation($conversation);
