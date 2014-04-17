<?php
include_once 'ApiClient.php';

use HelpScout\ApiClient;

$client = ApiClient::getInstance();
$client->setKey('example-key');

// valid convo id that already exists
$conversationId = 1522392;

// Message threads are ones created by users of Help Scout and will be emailed out to customers
$thread = new \HelpScout\model\thread\Message();
$thread->setBody('Hey there - sorry you\'re having issues. We\'ve contacted an engineer and he will get back with you shortly. Stay tuned.');

// Created by: required
// The ID given must be a registered user of Help Scout
$thread->setCreatedBy($client->getUserRefProxy(4));

$client->createThread($conversationId, $thread);

echo $thread->getId();

