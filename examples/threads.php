<?php
require __DIR__ . '/../vendor/autoload.php';
require '_credentials.php';

use HelpScout\Api\ApiClientFactory;
use HelpScout\Api\Conversations\Threads\PhoneThread;
use HelpScout\Api\Customers\Customer;
use HelpScout\Api\Conversations\Threads\Attachments\AttachmentFactory;
use HelpScout\Api\Support\Filesystem;
use HelpScout\Api\Entity\Collection;

$client = ApiClientFactory::createClient();
$client->useClientCredentials($appId, $appSecret);

$conversationId = 0;
$threads = $client->threads()->list($conversationId);

print_r($threads->getFirstPage()->toArray());

// Creating a new thread
$conversationId = 661099723;

$thread = new PhoneThread();
$customer = new Customer();
$customer->setId(163487350);
$thread->setCustomer($customer);
$thread->setText('test');

$factory = new AttachmentFactory(new Filesystem());
$file = $factory->make(__FILE__);
$thread->setAttachments(new Collection([$file]));

try {
    $client->threads()->create($conversationId, $thread);
} catch (\HelpScout\Api\Exception\ValidationErrorException $e) {
    var_dump($e->getError());
}

// Update thread
$conversationId = 0;
$threadId = 0;
$client->threads()->updateText(18, $threadId, 'I need help please');

// Get the source of a thread
try {
    $source = $client->threads()->getSource(1189656771, 3394140565);
} catch (\GuzzleHttp\Exception\ClientException $e) {
    if ($e->getResponse()->getStatusCode() === 404) {
        // thread's source not available
    }
}
print_r($source->getOriginal());
