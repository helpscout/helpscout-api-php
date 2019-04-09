<?php
require __DIR__ . '/../vendor/autoload.php';
require '_credentials.php';

use HelpScout\Api\ApiClientFactory;

$client = ApiClientFactory::createClient();

$tags = $client->useClientCredentials($appId, $appSecret)
    ->tags()
    ->list();

print_r($tags->getFirstPage()->toArray());
