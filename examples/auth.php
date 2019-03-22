<?php
require __DIR__ . '/../vendor/autoload.php';
require '_credentials.php';

use HelpScout\Api\ApiClientFactory;


// See https://developer.helpscout.com/mailbox-api/overview/authentication/#authorization-code-flow
$appId = '';
$appSecret = '';
$authorizationCode = '';

$client = ApiClientFactory::createClient();
$client = $client->swapAuthorizationCodeForReusableTokens(
    $appId,
    $appSecret,
    $authorizationCode
);

var_dump($client->getAuthenticator()->getTokens());

// Additional requests after exchanging the code use the access/refresh tokens
$users = $client->users()->list();
print_r($users->getFirstPage()->toArray());
