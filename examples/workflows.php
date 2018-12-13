<?php
require __DIR__ . '/../vendor/autoload.php';
require '_credentials.php';

use HelpScout\Api\ApiClientFactory;

$client = ApiClientFactory::createClient();
$client->useClientCredentials($appId, $appSecret);

$workflows = $client->workflows()->list();

print_r($workflows->getFirstPage()->toArray());
