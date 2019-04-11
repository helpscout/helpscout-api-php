<?php

use GuzzleHttp\Psr7\Request;
use HelpScout\Api\Webhooks\IncomingWebhook;

require __DIR__ . '/../vendor/autoload.php';

$body = json_encode(['id' => 123]);
$secret = 'asdfasdf';
$signature = '7S2+kN8bZsrdAEADJ+yYfWWsf/4=';

$headers = [
    'HTTP_X_HELPSCOUT_SIGNATURE' => $signature,
    'HTTP_X_HELPSCOUT_EVENT' => 'convo.deleted',
];

$request = new Request('POST', 'www.blah.blah', $headers, $body);

// You can use IncomingWebhook::makeFromGlobals($secret) instead of building your own request
$webhook = new IncomingWebhook($request, $secret);

$eventType = $webhook->getEventType();
if ($eventType === 'convo.deleted') {
    $obj = $webhook->getDataObject();
    echo $obj->id;
}
