<?php

declare(strict_types=1);

namespace HelpScout\Api\Tests\Customers;

use DateTime;
use HelpScout\Api\Customers\Customer;
use HelpScout\Api\Customers\Entry\Address;
use HelpScout\Api\Customers\Entry\ChatHandle;
use HelpScout\Api\Customers\Entry\Email;
use HelpScout\Api\Customers\Entry\Phone;
use HelpScout\Api\Customers\Entry\SocialProfile;
use HelpScout\Api\Customers\Entry\Website;
use PHPUnit\Framework\TestCase;

class CustomerTest extends TestCase
{
    public function testHydrate()
    {
        $customer = new Customer();
        $customer->hydrate([
            'id' => 12,
            'createdAt' => '2017-04-21T14:39:56Z',
            'updatedAt' => '2017-04-21T14:43:24Z',
            'firstName' => 'Big',
            'lastName' => 'Bird',
            'gender' => 'unknown',
            'jobTitle' => 'Entertainer',
            'location' => 'US',
            'organization' => 'Sesame Street',
            'photoType' => 'unknown',
            'photoUrl' => '',
            'background' => 'Big yellow bird',
            'age' => '52',
        ]);

        $this->assertSame(12, $customer->getId());
        $this->assertSame('Big', $customer->getFirstName());
        $this->assertSame('Bird', $customer->getLastName());
        $this->assertSame('unknown', $customer->getGender());
        $this->assertSame('Entertainer', $customer->getJobTitle());
        $this->assertSame('US', $customer->getLocation());
        $this->assertSame('Sesame Street', $customer->getOrganization());
        $this->assertSame('unknown', $customer->getPhotoType());
        $this->assertSame('', $customer->getPhotoUrl());
        $this->assertInstanceOf(DateTime::class, $customer->getCreatedAt());
        $this->assertSame('2017-04-21T14:39:56+00:00', $customer->getCreatedAt()->format('c'));
        $this->assertInstanceOf(DateTime::class, $customer->getUpdatedAt());
        $this->assertSame('2017-04-21T14:43:24+00:00', $customer->getUpdatedAt()->format('c'));
        $this->assertSame('Big yellow bird', $customer->getBackground());
        $this->assertSame('52', $customer->getAge());
    }

    public function testHydratesOneAddress()
    {
        $customer = new Customer();
        $customer->hydrate([
            'createdAt' => '2017-04-21T14:39:56Z',
            'updatedAt' => '2017-04-21T14:43:24Z',
        ], [
            'address' => [
                'city' => 'Norfolk',
            ],
        ]);

        // Only asserting one field here as the details of what's inflated are covered by the entity's tests
        $this->assertSame('Norfolk', $customer->getAddress()->getCity());
    }

    public function testHydratesManyChatHandles()
    {
        $customer = new Customer();
        $customer->hydrate([
            'createdAt' => '2017-04-21T14:39:56Z',
            'updatedAt' => '2017-04-21T14:43:24Z',
        ], [
            'chats' => [
                [
                    'id' => 123123,
                ],
                [
                    'id' => 456223,
                ],
            ],
        ]);

        $chats = $customer->getChatHandles();

        // Only asserting one field here as the details of what's inflated are covered by the entity's tests
        $this->assertInstanceOf(ChatHandle::class, $chats[0]);
        $this->assertSame(123123, $chats[0]->getId());

        $this->assertInstanceOf(ChatHandle::class, $chats[1]);
        $this->assertSame(456223, $chats[1]->getId());
    }

    public function testHydratesManyEmails()
    {
        $customer = new Customer();
        $customer->hydrate([
            'createdAt' => '2017-04-21T14:39:56Z',
            'updatedAt' => '2017-04-21T14:43:24Z',
        ], [
            'emails' => [
                [
                    'id' => 689,
                ],
                [
                    'id' => 798,
                ],
            ],
        ]);

        $emails = $customer->getEmails();

        // Only asserting one field here as the details of what's inflated are covered by the entity's tests
        $this->assertInstanceOf(Email::class, $emails[0]);
        $this->assertSame(689, $emails[0]->getId());

        $this->assertInstanceOf(Email::class, $emails[1]);
        $this->assertSame(798, $emails[1]->getId());
    }

    public function testHydratesManySocialProfiles()
    {
        $customer = new Customer();
        $customer->hydrate([
            'createdAt' => '2017-04-21T14:39:56Z',
            'updatedAt' => '2017-04-21T14:43:24Z',
        ], [
            'social_profiles' => [
                [
                    'id' => 1233,
                ],
                [
                    'id' => 4512,
                ],
            ],
        ]);

        $socialProfiles = $customer->getSocialProfiles();

        // Only asserting one field here as the details of what's inflated are covered by the entity's tests
        $this->assertInstanceOf(SocialProfile::class, $socialProfiles[0]);
        $this->assertSame(1233, $socialProfiles[0]->getId());

        $this->assertInstanceOf(SocialProfile::class, $socialProfiles[1]);
        $this->assertSame(4512, $socialProfiles[1]->getId());
    }

    public function testHydratesManyPhones()
    {
        $customer = new Customer();
        $customer->hydrate([
            'createdAt' => '2017-04-21T14:39:56Z',
            'updatedAt' => '2017-04-21T14:43:24Z',
        ], [
            'phones' => [
                [
                    'id' => 9966,
                ],
                [
                    'id' => 4433,
                ],
            ],
        ]);

        $phones = $customer->getPhones();

        // Only asserting one field here as the details of what's inflated are covered by the entity's tests
        $this->assertInstanceOf(Phone::class, $phones[0]);
        $this->assertSame(9966, $phones[0]->getId());

        $this->assertInstanceOf(Phone::class, $phones[1]);
        $this->assertSame(4433, $phones[1]->getId());
    }

    public function testHydratesManyWebsites()
    {
        $customer = new Customer();
        $customer->hydrate([
            'createdAt' => '2017-04-21T14:39:56Z',
            'updatedAt' => '2017-04-21T14:43:24Z',
        ], [
            'websites' => [
                [
                    'id' => 1390,
                ],
                [
                    'id' => 9530,
                ],
            ],
        ]);

        $websites = $customer->getWebsites();

        // Only asserting one field here as the details of what's inflated are covered by the entity's tests
        $this->assertInstanceOf(Website::class, $websites[0]);
        $this->assertSame(1390, $websites[0]->getId());

        $this->assertInstanceOf(Website::class, $websites[1]);
        $this->assertSame(9530, $websites[1]->getId());
    }

    public function testHydrateWithoutCreatedAt()
    {
        $customer = new Customer();
        $customer->hydrate([
            'id' => 12,
        ]);

        $this->assertNull($customer->getCreatedAt());
    }

    public function testHydrateWithoutUpdatedAt()
    {
        $customer = new Customer();
        $customer->hydrate([
            'id' => 12,
        ]);

        $this->assertNull($customer->getUpdatedAt());
    }

    public function testHydrateWithoutNameSuffixes()
    {
        $customer = new Customer();
        $customer->hydrate([
            'first' => 'John',
            'last' => 'Smith',
        ]);

        $this->assertSame('John', $customer->getFirstName());
        $this->assertSame('Smith', $customer->getLastName());
    }

    public function testExtract()
    {
        $customer = new Customer();
        $customer->setId(12);
        $customer->setCreatedAt(new DateTime('2017-04-21T14:39:56Z'));
        $customer->setUpdatedAt(new DateTime('2017-04-21T14:43:24Z'));
        $customer->setFirstName('Big');
        $customer->setLastName('Bird');
        $customer->setGender('unknown');
        $customer->setJobTitle('Entertainer');
        $customer->setLocation('US');
        $customer->setOrganization('Sesame Street');
        $customer->setPhotoType('unknown');
        $customer->setPhotoUrl('https://photos.me');
        $customer->setBackground('Big yellow bird');
        $customer->setAge('52');

        $this->assertSame([
            'id' => 12,
            'firstName' => 'Big',
            'lastName' => 'Bird',
            'gender' => 'unknown',
            'jobTitle' => 'Entertainer',
            'location' => 'US',
            'organization' => 'Sesame Street',
            'photoType' => 'unknown',
            'photoUrl' => 'https://photos.me',
            'background' => 'Big yellow bird',
            'age' => '52',
        ], $customer->extract());
    }

    /**
     * Commonly in v2 of the API we see scenarios where if a "Customer" has an email or id it'll use the existing
     * Customer associated with those, otherwise it'll create a new Customer.  When extracting a Customer it'll
     * most likely be used for Creating something, so including email as a primary attribute this cleaner.
     */
    public function testExtractsEmailAsAttribute()
    {
        $customer = new Customer();

        $email = new Email();
        $email->setValue('tester@mysite.com');

        $customer->addEmail($email);

        $extracted = $customer->extract();
        $this->assertArrayHasKey('email', $extracted);
        $this->assertEquals('tester@mysite.com', $extracted['email']);
    }

    public function testExtractNewEntity()
    {
        $customer = new Customer();

        $this->assertSame([], $customer->extract());
    }

    public function testExtractAddress()
    {
        $customer = new Customer();
        $address = new Address();
        $address->setPostalCode('42301');
        $customer->setAddress($address);

        $extracted = $customer->extract();
        $this->assertArrayHasKey('address', $extracted);
        $this->assertSame('42301', $extracted['address']['postalCode']);
    }

    public function testAddChat()
    {
        $customer = new Customer();
        $this->assertEmpty($customer->getChatHandles());

        $chat = new ChatHandle();
        $customer->addChatHandle($chat);
        $this->assertSame($chat, $customer->getChatHandles()->toArray()[0]);
    }

    public function testExtractChatHandles()
    {
        $customer = new Customer();
        $chatHandle = new ChatHandle();
        $chatHandle->setValue('jsmith');
        $customer->addChatHandle($chatHandle);

        $extracted = $customer->extract();
        $this->assertArrayHasKey('chats', $extracted);
        $this->assertSame('jsmith', $extracted['chats'][0]['value']);
    }

    public function testAddEmail()
    {
        $customer = new Customer();
        $this->assertEmpty($customer->getEmails());

        $email = new Email();
        $customer->addEmail($email);
        $this->assertSame($email, $customer->getEmails()->toArray()[0]);
    }

    public function testExtractEmails()
    {
        $customer = new Customer();
        $email = new Email();
        $email->setValue('customer@email.com');
        $customer->addEmail($email);

        $extracted = $customer->extract();
        $this->assertArrayHasKey('emails', $extracted);
        $this->assertSame('customer@email.com', $extracted['emails'][0]['value']);
    }

    public function testAddPhone()
    {
        $customer = new Customer();
        $this->assertEmpty($customer->getPhones());

        $phone = new Phone();
        $customer->addPhone($phone);
        $this->assertSame($phone, $customer->getPhones()->toArray()[0]);
    }

    public function testExtractPhones()
    {
        $customer = new Customer();
        $phone = new Phone();
        $phone->setValue('45551234321');
        $customer->addPhone($phone);

        $extracted = $customer->extract();
        $this->assertArrayHasKey('phones', $extracted);
        $this->assertSame('45551234321', $extracted['phones'][0]['value']);
    }

    public function testAddSocialProfiles()
    {
        $customer = new Customer();
        $this->assertEmpty($customer->getSocialProfiles());

        $profile = new SocialProfile();
        $customer->addSocialProfile($profile);
        $this->assertSame($profile, $customer->getSocialProfiles()->toArray()[0]);
    }

    public function testExtractSocialProfiles()
    {
        $customer = new Customer();
        $socialProfile = new SocialProfile();
        $socialProfile->setValue('miwjelde');
        $customer->addSocialProfile($socialProfile);

        $extracted = $customer->extract();
        $this->assertArrayHasKey('socialProfiles', $extracted);
        $this->assertSame('miwjelde', $extracted['socialProfiles'][0]['value']);
    }

    public function testAddWebsites()
    {
        $customer = new Customer();
        $this->assertEmpty($customer->getWebsites());

        $website = new Website();
        $customer->addWebsite($website);
        $this->assertSame($website, $customer->getWebsites()->toArray()[0]);
    }

    public function testExtractWebsites()
    {
        $customer = new Customer();
        $website = new Website();
        $website->setValue('http://google.com');
        $customer->addWebsite($website);

        $extracted = $customer->extract();
        $this->assertArrayHasKey('websites', $extracted);
        $this->assertSame('http://google.com', $extracted['websites'][0]['value']);
    }
}
