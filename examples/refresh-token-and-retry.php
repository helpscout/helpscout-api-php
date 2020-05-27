<?php
require __DIR__ . '/../vendor/autoload.php';
require '_credentials.php';

use HelpScout\Api\ApiClient;
use HelpScout\Api\ApiClientFactory;

$client = ApiClientFactory::createClient();
$client = $client->useClientCredentials($appId, $appSecret);

/**
 * This example shows how to catch an Auth exception, refresh the token and retry the same work again.
 */
function autoRefreshToken(ApiClient $client, Closure $workToRetry) {
    $attempts = 0;
    do {
        try {
            return $workToRetry($client);
        } catch (\HelpScout\Api\Exception\AuthenticationException $e) {
            $client->getAuthenticator()->fetchAccessAndRefreshToken();
        }
        $attempts++;
    } while($attempts < 1);

    throw new RuntimeException('Authentication failure loop encountered');
}

$users = autoRefreshToken($client, function (ApiClient $client) {
    return $client->users()->list();
});

print_r($users->getFirstPage()->toArray());