<?php
require __DIR__ . '/../vendor/autoload.php';
require '_credentials.php';

use HelpScout\Api\ApiClientFactory;
use HelpScout\Api\Customers\CustomerFilters;
use HelpScout\Api\Customers\Customer;
use HelpScout\Api\Customers\Entry\Email;

$client = ApiClientFactory::createClient();
$client = $client->useClientCredentials($appId, $appSecret);

// Create Customer
$customer = new Customer();
$customer->setFirstName('John');
$customer->setLastName('Smith');
$customer->addEmail(new Email([
    'email' => "my-customer@their-business.com",
    'type' => 'work',
]));
$client->customers()->create($customer);

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
