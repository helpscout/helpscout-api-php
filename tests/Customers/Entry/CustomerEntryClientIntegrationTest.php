<?php

declare(strict_types=1);

namespace HelpScout\Api\Tests\Customers\Entry;

use HelpScout\Api\Customers\Entry\Address;
use HelpScout\Api\Customers\Entry\ChatHandle;
use HelpScout\Api\Customers\Entry\Email;
use HelpScout\Api\Customers\Entry\Phone;
use HelpScout\Api\Customers\Entry\SocialProfile;
use HelpScout\Api\Customers\Entry\Website;
use HelpScout\Api\Tests\ApiClientIntegrationTestCase;

/**
 * @group integration
 */
class CustomerEntryClientIntegrationTest extends ApiClientIntegrationTestCase
{
    public function testCreateCustomerAddress()
    {
        $this->stubResponse($this->getResponse(201));

        $address = new Address();
        $address->hydrate([
            'city' => 'Dallas',
            'lines' => ['123 West Main St', 'Suite 123'],
            'state' => 'TX',
            'postalCode' => '74206',
            'country' => 'US',
        ]);

        $this->client->customerEntry()->createAddress(12, $address);

        $this->verifyRequestWithData(
            'https://api.helpscout.net/v2/customers/12/address',
            'POST',
            [
                'city' => 'Dallas',
                'lines' => ['123 West Main St', 'Suite 123'],
                'state' => 'TX',
                'postalCode' => '74206',
                'country' => 'US',
            ]
        );
    }

    public function testUpdateCustomerAddress()
    {
        $this->stubResponse($this->getResponse(204));

        $address = new Address();
        $address->hydrate([
            'city' => 'Dallas',
            'lines' => ['123 West Main St', 'Suite 123'],
            'state' => 'TX',
            'postalCode' => '74206',
            'country' => 'US',
        ]);

        $this->client->customerEntry()->updateAddress(12, $address);

        $this->verifyRequestWithData(
            'https://api.helpscout.net/v2/customers/12/address',
            'PUT',
            [
                'city' => 'Dallas',
                'lines' => ['123 West Main St', 'Suite 123'],
                'state' => 'TX',
                'postalCode' => '74206',
                'country' => 'US',
            ]
        );
    }

    public function testDeleteCustomerAddress()
    {
        $this->stubResponse($this->getResponse(204));

        $this->client->customerEntry()->deleteAddress(12);

        $this->verifySingleRequest(
            'https://api.helpscout.net/v2/customers/12/address',
            'DELETE'
        );
    }

    public function testCreateCustomerChat()
    {
        $this->stubResponse($this->getResponse(201));

        $chat = new ChatHandle();
        $chat->hydrate([
            'value' => 'Hello there',
            'type' => 'twitter',
        ]);

        $this->client->customerEntry()->createChat(12, $chat);

        $this->verifyRequestWithData(
            'https://api.helpscout.net/v2/customers/12/chats',
            'POST',
            [
                'value' => 'Hello there',
                'type' => 'twitter',
            ]
        );
    }

    public function testUpdateCustomerChat()
    {
        $this->stubResponse($this->getResponse(204));

        $chat = new ChatHandle();
        $chat->hydrate([
            'id' => 42,
            'value' => 'Hello there',
            'type' => 'twitter',
        ]);

        $this->client->customerEntry()->updateChat(12, $chat);

        $this->verifyRequestWithData(
            'https://api.helpscout.net/v2/customers/12/chats/42',
            'PUT',
            [
                'value' => 'Hello there',
                'type' => 'twitter',
            ]
        );
    }

    public function testDeleteCustomerChat()
    {
        $this->stubResponse($this->getResponse(204));

        $this->client->customerEntry()->deleteChat(12, 42);

        $this->verifySingleRequest(
            'https://api.helpscout.net/v2/customers/12/chats/42',
            'DELETE'
        );
    }

    public function testCreateCustomerEmail()
    {
        $this->stubResponse($this->getResponse(201));

        $email = new Email();
        $email->hydrate([
            'value' => 'tom@helpscout.com',
            'type' => 'work',
        ]);

        $this->client->customerEntry()->createEmail(12, $email);

        $this->verifyRequestWithData(
            'https://api.helpscout.net/v2/customers/12/emails',
            'POST',
            [
                'value' => 'tom@helpscout.com',
                'type' => 'work',
            ]
        );
    }

    public function testUpdateCustomerEmail()
    {
        $this->stubResponse($this->getResponse(204));

        $email = new Email();
        $email->hydrate([
            'id' => 42,
            'value' => 'tom@helpscout.com',
            'type' => 'work',
        ]);

        $this->client->customerEntry()->updateEmail(12, $email);

        $this->verifyRequestWithData(
            'https://api.helpscout.net/v2/customers/12/emails/42',
            'PUT',
            [
                'value' => 'tom@helpscout.com',
                'type' => 'work',
            ]
        );
    }

    public function testDeleteCustomerEmail()
    {
        $this->stubResponse($this->getResponse(204));

        $this->client->customerEntry()->deleteEmail(12, 42);

        $this->verifySingleRequest(
            'https://api.helpscout.net/v2/customers/12/emails/42',
            'DELETE'
        );
    }

    public function testCreateCustomerPhone()
    {
        $this->stubResponse($this->getResponse(201));

        $phone = new Phone();
        $phone->hydrate([
            'value' => '123456789',
            'type' => 'work',
        ]);

        $this->client->customerEntry()->createPhone(12, $phone);

        $this->verifyRequestWithData(
            'https://api.helpscout.net/v2/customers/12/phones',
            'POST',
            [
                'value' => '123456789',
                'type' => 'work',
            ]
        );
    }

    public function testUpdateCustomerPhone()
    {
        $this->stubResponse($this->getResponse(204));

        $phone = new Phone();
        $phone->hydrate([
            'id' => 42,
            'value' => '123456789',
            'type' => 'work',
        ]);

        $this->client->customerEntry()->updatePhone(12, $phone);

        $this->verifyRequestWithData(
            'https://api.helpscout.net/v2/customers/12/phones/42',
            'PUT',
            [
                'value' => '123456789',
                'type' => 'work',
            ]
        );
    }

    public function testDeleteCustomerPhone()
    {
        $this->stubResponse($this->getResponse(204));

        $this->client->customerEntry()->deletePhone(12, 42);

        $this->verifySingleRequest(
            'https://api.helpscout.net/v2/customers/12/phones/42',
            'DELETE'
        );
    }

    public function testCreateCustomerSocialProfile()
    {
        $this->stubResponse($this->getResponse(201));

        $socialProfile = new SocialProfile();
        $socialProfile->hydrate([
            'value' => 'tompedals',
            'type' => 'twitter',
        ]);

        $this->client->customerEntry()->createSocialProfile(12, $socialProfile);

        $this->verifyRequestWithData(
            'https://api.helpscout.net/v2/customers/12/social-profiles',
            'POST',
            [
                'value' => 'tompedals',
                'type' => 'twitter',
            ]
        );
    }

    public function testUpdateCustomerSocialProfile()
    {
        $this->stubResponse($this->getResponse(204));

        $socialProfile = new SocialProfile();
        $socialProfile->hydrate([
            'id' => 42,
            'value' => 'tompedals',
            'type' => 'twitter',
        ]);

        $this->client->customerEntry()->updateSocialProfile(12, $socialProfile);

        $this->verifyRequestWithData(
            'https://api.helpscout.net/v2/customers/12/social-profiles/42',
            'PUT',
            [
                'value' => 'tompedals',
                'type' => 'twitter',
            ]
        );
    }

    public function testDeleteCustomerSocialProfile()
    {
        $this->stubResponse($this->getResponse(204));

        $this->client->customerEntry()->deleteSocialProfile(12, 42);

        $this->verifySingleRequest(
            'https://api.helpscout.net/v2/customers/12/social-profiles/42',
            'DELETE'
        );
    }

    public function testCreateCustomerWebsite()
    {
        $this->stubResponse($this->getResponse(201));

        $website = new Website();
        $website->hydrate([
            'value' => 'https://www.helpscout.com',
        ]);

        $this->client->customerEntry()->createWebsite(12, $website);

        $this->verifyRequestWithData(
            'https://api.helpscout.net/v2/customers/12/websites',
            'POST',
            ['value' => 'https://www.helpscout.com']
        );
    }

    public function testUpdateCustomerWebsite()
    {
        $this->stubResponse($this->getResponse(204));

        $website = new Website();
        $website->hydrate([
            'id' => 42,
            'value' => 'https://www.helpscout.com',
        ]);

        $this->client->customerEntry()->updateWebsite(12, $website);

        $this->verifyRequestWithData(
            'https://api.helpscout.net/v2/customers/12/websites/42',
            'PUT',
            ['value' => 'https://www.helpscout.com']
        );
    }

    public function testDeleteCustomerWebsite()
    {
        $this->stubResponse($this->getResponse(204));

        $this->client->customerEntry()->deleteWebsite(12, 42);

        $this->verifySingleRequest(
            'https://api.helpscout.net/v2/customers/12/websites/42',
            'DELETE'
        );
    }
}
