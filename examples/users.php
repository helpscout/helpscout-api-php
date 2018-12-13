<?php
require __DIR__ . '/../vendor/autoload.php';
require '_credentials.php';

use HelpScout\Api\ApiClientFactory;

$client = ApiClientFactory::createClient();
$client->useClientCredentials($appId, $appSecret);

$users = $client->users()->list();

print_r($users->getFirstPage()->toArray());
