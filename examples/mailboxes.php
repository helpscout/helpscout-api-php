<?php
require __DIR__ . '/../vendor/autoload.php';
require '_credentials.php';

use HelpScout\Api\ApiClientFactory;
use HelpScout\Api\Customers\CustomerFilters;
use HelpScout\Api\Customers\Customer;
use HelpScout\Api\Customers\Entry\Email;

$client = ApiClientFactory::createClient();
$client = $client->useClientCredentials($appId, $appSecret);

// GET mailbox
$mailbox = $client->mailboxes()->get(4938);

// List mailboxes
$mailboxes = $client->mailboxes()
    ->list();

// show the name of the mailboxes on the first page of results
foreach($mailboxes->getFirstPage() as $mailbox) {
    echo $mailbox->getName().PHP_EOL;
}