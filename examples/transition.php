<?php
require __DIR__ . '/../vendor/autoload.php';
require '_credentials.php';

use HelpScout\Api\ApiClientFactory;

$client = ApiClientFactory::createClient();

$tokens = $client->useLegacyToken($clientId, $apiKey)
    ->getTokens();

print_r($tokens);
