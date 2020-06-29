<?php
require __DIR__ . '/../vendor/autoload.php';
require '_credentials.php';

use HelpScout\Api\ApiClientFactory;
use HelpScout\Api\Http\Authenticator;

/**
 * The ApiClient can automatically refresh an expired token for you upon a failed request.
 * When building the client a callback can be provided that will be executed immediately after
 * the new token is issued.
 */
$client = ApiClientFactory::createClient([], function (Authenticator $authenticator) {
    echo 'New token: '.$authenticator->accessToken().PHP_EOL;
});

$expiredRefreshToken = '';
$client = $client->useRefreshToken($appId, $appSecret, $expiredRefreshToken);

// Try to obtain a customer
$customer = $client->customers()->get(347089737);
var_dump($customer->getFirstEmail());
