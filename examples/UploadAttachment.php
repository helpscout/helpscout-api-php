<?php
include_once 'ApiClient.php';

use HelpScout\ApiClient;
use HelpScout\model\Attachment;

$client = ApiClient::getInstance();
$client->setKey('example-key');

$attachment = new Attachment();
$attachment->setFileName('photo.png');
$attachment->setMimeType('image/png');
$attachment->setData(file_get_contents('/tmp/photo.png'));

$client->createAttachment($attachment);

// at this point, the image as been uploaded and is waiting to be attached to a conversation

// Now let's build a conversation
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

// add any and all previously uploaded attachments. Help Scout will take
// the attachments you've already uploaded and associate them with this conversation.
$thread->setAttachments(array($attachment));

// Create by: required
$thread->setCreatedBy($customerRef);

$conversation->addLineItem($thread);

$client->createConversation($conversation);

echo $conversation->getId();

