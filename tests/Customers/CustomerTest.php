<?php

declare(strict_types=1);

namespace HelpScout\Api\Tests\Customers;

use DateTime;
use HelpScout\Api\Customers\Customer;
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
        $customer->setPhotoUrl('');
        $customer->setBackground('Big yellow bird');
        $customer->setAge('52');

        $this->assertSame([
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
        ], $customer->extract());
    }

    public function testExtractNewEntity()
    {
        $customer = new Customer();

        $this->assertSame([
            'firstName' => null,
            'lastName' => null,
            'gender' => null,
            'jobTitle' => null,
            'location' => null,
            'organization' => null,
            'photoType' => null,
            'photoUrl' => null,
            'background' => null,
            'age' => null,
        ], $customer->extract());
    }

    public function testAddChat()
    {
        $customer = new Customer();
        $this->assertEmpty($customer->getChatHandles());

        $chat = new ChatHandle();
        $customer->addChatHandle($chat);
        $this->assertSame($chat, $customer->getChatHandles()->toArray()[0]);
    }

    public function testAddEmail()
    {
        $customer = new Customer();
        $this->assertEmpty($customer->getEmails());

        $email = new Email();
        $customer->addEmail($email);
        $this->assertSame($email, $customer->getEmails()->toArray()[0]);
    }

    public function testAddPhone()
    {
        $customer = new Customer();
        $this->assertEmpty($customer->getPhones());

        $phone = new Phone();
        $customer->addPhone($phone);
        $this->assertSame($phone, $customer->getPhones()->toArray()[0]);
    }

    public function testAddSocialProfiles()
    {
        $customer = new Customer();
        $this->assertEmpty($customer->getSocialProfiles());

        $profile = new SocialProfile();
        $customer->addSocialProfile($profile);
        $this->assertSame($profile, $customer->getSocialProfiles()->toArray()[0]);
    }

    public function testAddWebsites()
    {
        $customer = new Customer();
        $this->assertEmpty($customer->getWebsites());

        $website = new Website();
        $customer->addWebsite($website);
        $this->assertSame($website, $customer->getWebsites()->toArray()[0]);
    }
}
