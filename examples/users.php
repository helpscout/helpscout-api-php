<?php
require __DIR__ . '/../vendor/autoload.php';
require '_credentials.php';

use HelpScout\Api\ApiClientFactory;
use HelpScout\Api\Users\UserFilters;

$client = ApiClientFactory::createClient();
$client->useClientCredentials($appId, $appSecret);

// List users
$users = $client->users()->list();

$filters = (new UserFilters())
    ->inMailbox(197271);

$users = $client->users()->list($filters);

print_r($users->getFirstPage()->toArray());
