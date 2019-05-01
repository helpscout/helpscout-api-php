<?php
require __DIR__ . '/../vendor/autoload.php';
require '_credentials.php';

use HelpScout\Api\ApiClientFactory;
use HelpScout\Api\Conversations\ConversationFilters;
use HelpScout\Api\Conversations\Status;

$client = ApiClientFactory::createClient();
$client = $client->useClientCredentials($appId, $appSecret);

//// GET mailbox
//$mailbox = $client->mailboxes()->get(4938);

// List mailboxes
$mailboxes = $client->mailboxes()
    ->list();

// show the name of the mailboxes on the first page of results
foreach($mailboxes as $mailbox) {

    // Find out how many active conversations we have for each mailbox
    $filters = (new ConversationFilters())
        ->withMailbox($mailbox->getId())
        ->withStatus(Status::ACTIVE);
    $conversations = $client->conversations()->list($filters);

    echo $mailbox->getName().' ('.number_format($conversations->getTotalElementCount()).') '.PHP_EOL;
}