<?php
require __DIR__ . '/../vendor/autoload.php';
require '_credentials.php';

use HelpScout\Api\ApiClientFactory;

$client = ApiClientFactory::createClient();
$client->useClientCredentials($appId, $appSecret);

// List all teams
$users = $client->teams()->list();
print_r($users->getFirstPage()->toArray());

// List the members of a team
$teamMembers = $client->teams()->members(115780);


