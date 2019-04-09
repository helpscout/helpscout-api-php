<?php
require __DIR__ . '/../vendor/autoload.php';
require '_credentials.php';

use HelpScout\Api\ApiClientFactory;
use HelpScout\Api\Webhooks\Webhook;

$client = ApiClientFactory::createClient();

$request = new Webhook();
$request->hydrate([
    'url' => 'http://bad-url.com',
    'events' => ['convo.assigned', 'convo.moved'],
    'secret' => 'mZ9XbGHodY'
]);

$client->useClientCredentials($appId, $appSecret);

// Create webhook
$id = $client->webhooks()->create($request);

// GET webhook
$webhook = $client->webhooks()->get($id);

print_r($webhook->extract());

// Update Webhook
$webhook->setUrl('http://bad-url.com/really_really_bad');
$webhook->setSecret('mZ9XbGHodY');
$client->webhooks()->update($webhook);

// List webhooks
$webhooks = $client->webhooks()->list()
    ->getFirstPage()
    ->toArray();

foreach ($webhooks as $hook) {
    $hookId = $hook->getId();
    echo "Deleting {$hookId}" . PHP_EOL;
    $client->webhooks()->delete($hookId);
    echo "Deleted {$hookId}" . PHP_EOL;
}
