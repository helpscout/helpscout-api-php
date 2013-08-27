<?php

use HelpScout\ApiClient;

$client = ApiClient::getInstance();
$client->setKey('example-key');

$conversation = $client->getConversation(1);
$conversation->setSubject("New Subject");
$conversation->setStatus("pending");

// Change the owner of the conversation
$owner = new \HelpScout\model\ref\PersonRef();
$owner->setId(100);
$owner->setType("user");
$conversation->setOwner($owner);

// Change the mailbox of the conversation
$mailbox = new \HelpScout\model\ref\MailboxRef();
$mailbox->setId(1);
$conversation->setMailbox($mailbox);

// Update the conversation tags. Existing tags not
// in this list will be deleted.
$conversation->setTags(array("tag1", "tag2"));

$client->updateConversation($conversation);
