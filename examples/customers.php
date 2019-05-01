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
    ->list();

echo 'There are '.$customers->getTotalPageCount().' page(s) of results'.PHP_EOL;

// show the first name of the customers on the first page of results
foreach($customers as $customer) {
    echo $customer->getFirstName().PHP_EOL;
}

$filters = (new CustomerFilters())
    ->withFirstName('John')
    ->withLastName('Smith')
    ->withMailbox('12')
    ->withModifiedSince(new DateTime('last month'))
    // See https://developer.helpscout.com/mailbox-api/endpoints/customers/list/#query for details on what you can do with query
    ->withQuery('email:"alan@easycrypto.nz"')
    ->withSortField('createdAt')
    ->withSortOrder('asc');

$customers = $client->customers()->list($filters);