<?php

declare(strict_types=1);

namespace HelpScout\Api\Tests\Customers;

use HelpScout\Api\Customers\Customer;
use HelpScout\Api\Customers\CustomerFilters;
use HelpScout\Api\Customers\CustomerRequest;
use HelpScout\Api\Customers\Entry\Address;
use HelpScout\Api\Tests\ApiClientIntegrationTestCase;
use HelpScout\Api\Tests\Payloads\CustomerPayloads;

/**
 * @group integration
 */
class CustomerClientIntegrationTest extends ApiClientIntegrationTestCase
{
    public function testCreateCustomer()
    {
        $this->stubResponse($this->getResponse(201));

        $customer = new Customer();
        $customer->setFirstName('Big');
        $customer->setLastName('Bird');

        $this->client->customers()->create($customer);

        $this->verifyRequestWithData(
            'https://api.helpscout.net/v2/customers',
            'POST',
            [
                'firstName' => 'Big',
                'lastName' => 'Bird',
            ]
        );
    }

    public function testUpdateCustomer()
    {
        $this->stubResponse($this->getResponse(204));

        $customer = new Customer();
        $customer->setId(12);
        $customer->setFirstName('Big');
        $customer->setLastName('Bird');

        $this->client->customers()->update($customer);

        $this->verifyRequestWithData(
            'https://api.helpscout.net/v2/customers/12',
            'PUT',
            [
                'id' => 12,
                'firstName' => 'Big',
                'lastName' => 'Bird',
            ]
        );
    }

    public function testGetCustomer()
    {
        $this->stubResponse(
            $this->getResponse(200, CustomerPayloads::getCustomer(1))
        );

        $customer = $this->client->customers()->get(1);

        $this->assertInstanceOf(Customer::class, $customer);
        $this->assertSame(1, $customer->getId());

        $this->verifySingleRequest(
            'https://api.helpscout.net/v2/customers/1'
        );
    }

    public function testGetCustomerPreloadsAddress()
    {
        $this->stubResponses([
            $this->getResponse(200, CustomerPayloads::getCustomer(1)),
            $this->getResponse(200, CustomerPayloads::getAddress(1)),
        ]);

        $request = (new CustomerRequest())
            ->withAddress();

        $customer = $this->client->customers()->get(1, $request);
        $address = $customer->getAddress();

        $this->assertInstanceOf(Address::class, $address);

        $this->verifyMultipleRequests([
            ['GET', 'https://api.helpscout.net/v2/customers/1'],
            ['GET', 'https://api.helpscout.net/v2/customers/1/address'],
        ]);
    }

    public function testGetCustomerPreloadsChats()
    {
        $this->stubResponses([
            $this->getResponse(200, CustomerPayloads::getCustomer(1)),
            $this->getResponse(200, CustomerPayloads::getChats(1)),
        ]);

        $request = (new CustomerRequest())
            ->withChats();

        $customer = $this->client->customers()->get(1, $request);
        $chats = $customer->getChats();

        $this->assertCount(1, $chats);

        $this->verifyMultipleRequests([
            ['GET', 'https://api.helpscout.net/v2/customers/1'],
            ['GET', 'https://api.helpscout.net/v2/customers/1/chats'],
        ]);
    }

    public function testGetCustomerPreloadsEmails()
    {
        $this->stubResponses([
            $this->getResponse(200, CustomerPayloads::getCustomer(1)),
            $this->getResponse(200, CustomerPayloads::getEmails(1)),
        ]);

        $request = (new CustomerRequest())
            ->withEmails();

        $customer = $this->client->customers()->get(1, $request);
        $emails = $customer->getEmails();

        $this->assertCount(1, $emails);

        $this->verifyMultipleRequests([
            ['GET', 'https://api.helpscout.net/v2/customers/1'],
            ['GET', 'https://api.helpscout.net/v2/customers/1/emails'],
        ]);
    }

    public function testGetCustomerPreloadsPhones()
    {
        $this->stubResponses([
            $this->getResponse(200, CustomerPayloads::getCustomer(1)),
            $this->getResponse(200, CustomerPayloads::getPhones(1)),
        ]);

        $request = (new CustomerRequest())
            ->withPhones();

        $customer = $this->client->customers()->get(1, $request);
        $phones = $customer->getPhones();

        $this->assertCount(1, $phones);

        $this->verifyMultipleRequests([
            ['GET', 'https://api.helpscout.net/v2/customers/1'],
            ['GET', 'https://api.helpscout.net/v2/customers/1/phones'],
        ]);
    }

    public function testGetCustomerPreloadsSocialProfiles()
    {
        $this->stubResponses([
            $this->getResponse(200, CustomerPayloads::getCustomer(1)),
            $this->getResponse(200, CustomerPayloads::getSocialProfiles(1)),
        ]);

        $request = (new CustomerRequest())
            ->withSocialProfiles();

        $customer = $this->client->customers()->get(1, $request);
        $socialProfiles = $customer->getSocialProfiles();

        $this->assertCount(1, $socialProfiles);

        $this->verifyMultipleRequests([
            ['GET', 'https://api.helpscout.net/v2/customers/1'],
            ['GET', 'https://api.helpscout.net/v2/customers/1/social-profiles'],
        ]);
    }

    public function testGetCustomerPreloadsWebsites()
    {
        $this->stubResponses([
            $this->getResponse(200, CustomerPayloads::getCustomer(1)),
            $this->getResponse(200, CustomerPayloads::getWebsites(1)),
        ]);

        $request = (new CustomerRequest())
            ->withWebsites();

        $customer = $this->client->customers()->get(1, $request);
        $websites = $customer->getWebsites();

        $this->assertCount(1, $websites);

        $this->verifyMultipleRequests([
            ['GET', 'https://api.helpscout.net/v2/customers/1'],
            ['GET', 'https://api.helpscout.net/v2/customers/1/websites'],
        ]);
    }

    public function testGetCustomers()
    {
        $this->stubResponse(
            $this->getResponse(200, CustomerPayloads::getCustomers(1, 10))
        );

        $customers = $this->client->customers()->list();

        $this->assertCount(10, $customers);
        $this->assertInstanceOf(Customer::class, $customers[0]);

        $this->verifySingleRequest(
            'https://api.helpscout.net/v2/customers'
        );
    }

    public function testGetCustomersWithFilters()
    {
        $this->stubResponse(
            $this->getResponse(200, CustomerPayloads::getCustomers(1, 10))
        );

        $filters = (new CustomerFilters())
            ->withFirstName('Tom')
            ->withLastName('Graham');

        $customers = $this->client->customers()->list($filters);

        $this->assertCount(10, $customers);
        $this->assertInstanceOf(Customer::class, $customers[0]);

        $this->verifySingleRequest(
            'https://api.helpscout.net/v2/customers?firstName=Tom&lastName=Graham'
        );
    }

    public function testGetCustomersPreloadsAddresses()
    {
        $this->stubResponses([
            $this->getResponse(200, CustomerPayloads::getCustomers(1, 2)),
            $this->getResponse(200, CustomerPayloads::getAddress(1)),
            $this->getResponse(200, CustomerPayloads::getAddress(2)),
        ]);

        $request = (new CustomerRequest())
            ->withAddress();

        $customers = $this->client->customers()->list(null, $request);

        $this->assertCount(2, $customers);
        $this->assertInstanceOf(Address::class, $customers[0]->getAddress());
        $this->assertInstanceOf(Address::class, $customers[1]->getAddress());

        $this->verifyMultipleRequests([
            ['GET', 'https://api.helpscout.net/v2/customers'],
            ['GET', 'https://api.helpscout.net/v2/customers/1/address'],
            ['GET', 'https://api.helpscout.net/v2/customers/2/address'],
        ]);
    }

    public function testGetCustomersWithEmptyCollection()
    {
        $this->stubResponse(
            $this->getResponse(200, CustomerPayloads::getCustomers(1, 0))
        );

        $customers = $this->client->customers()->list();

        $this->assertCount(0, $customers);

        $this->verifySingleRequest(
            'https://api.helpscout.net/v2/customers'
        );
    }

    public function testGetCustomersParsesPageMetadata()
    {
        $this->stubResponse(
            $this->getResponse(200, CustomerPayloads::getCustomers(3, 35))
        );

        $customers = $this->client->customers()->list();

        $this->assertSame(3, $customers->getPageNumber());
        $this->assertSame(10, $customers->getPageSize());
        $this->assertSame(10, $customers->getPageElementCount());
        $this->assertSame(35, $customers->getTotalElementCount());
        $this->assertSame(4, $customers->getTotalPageCount());
    }

    public function testGetCustomersLazyLoadsPages()
    {
        $this->stubResponses([
            $this->getResponse(200, CustomerPayloads::getCustomers(1, 20)),
            $this->getResponse(200, CustomerPayloads::getCustomers(2, 20)),
        ]);

        $customers = $this->client->customers()->list()->getPage(2);

        $this->assertCount(10, $customers);
        $this->assertInstanceOf(Customer::class, $customers[0]);

        $this->verifyMultipleRequests([
            ['GET', 'https://api.helpscout.net/v2/customers'],
            ['GET', 'https://api.helpscout.net/v2/customers?page=2'],
        ]);
    }
}
