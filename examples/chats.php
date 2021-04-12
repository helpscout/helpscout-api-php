<?php
require __DIR__ . '/../vendor/autoload.php';
require '_credentials.php';

use HelpScout\Api\ApiClientFactory;

$client = ApiClientFactory::createClient();
$client = $client->useClientCredentials($appId, $appSecret);

// GET chat
$chat = $client->chats()->get('6d6c31e2-fc1c-4c12-8845-6b66d350d855');
var_dump($chat);

// GET chat events
$events = $client->chats()->events('6d6c31e2-fc1c-4c12-8845-6b66d350d855');
var_dump($events);