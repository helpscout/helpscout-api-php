<?php
require __DIR__ . '/../vendor/autoload.php';
require '_credentials.php';

use HelpScout\Api\ApiClientFactory;
use HelpScout\Api\Customers\CustomerFilters;

$client = ApiClientFactory::createClient();
$client = $client->useClientCredentials($appId, $appSecret);

// GET customers
$customer = $client->customers()->get(161694345);

// List customers
$customers = $client->customers()
    ->list()
    ->getFirstPage()
    ->toArray();

$filters = (new CustomerFilters())
    ->withFirstName('John')
    ->withLastName('Smith')
    ->withMailbox('12')
    ->withModifiedSince(new DateTime('last month'))
    ->withQuery('query')
    ->withSortField('createdAt')
    ->withSortOrder('asc');

$customers = $client->customers()->list($filters);
