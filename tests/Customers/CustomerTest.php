<?php

declare(strict_types=1);

namespace HelpScout\Api\Tests\Customers;

use DateTime;
use HelpScout\Api\Customers\Customer;
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
}
