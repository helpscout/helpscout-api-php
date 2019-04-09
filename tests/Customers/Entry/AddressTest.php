<?php

declare(strict_types=1);

namespace HelpScout\Api\Tests\Customers\Entry;

use HelpScout\Api\Customers\Entry\Address;
use PHPUnit\Framework\TestCase;

class AddressTest extends TestCase
{
    public function testHydrate()
    {
        $address = new Address();
        $address->hydrate([
            'city' => 'Dallas',
            'lines' => ['123 West Main St', 'Suite 123'],
            'state' => 'TX',
            'postalCode' => '74206',
            'country' => 'US',
        ]);

        $this->assertSame('Dallas', $address->getCity());
        $this->assertSame(['123 West Main St', 'Suite 123'], $address->getLines());
        $this->assertSame('TX', $address->getState());
        $this->assertSame('74206', $address->getPostalCode());
        $this->assertSame('US', $address->getCountry());
    }

    public function testExtract()
    {
        $address = new Address();
        $address->setCity('Dallas');
        $address->setLines(['123 West Main St', 'Suite 123']);
        $address->setState('TX');
        $address->setPostalCode('74206');
        $address->setCountry('US');

        $this->assertSame([
            'city' => 'Dallas',
            'lines' => ['123 West Main St', 'Suite 123'],
            'state' => 'TX',
            'postalCode' => '74206',
            'country' => 'US',
        ], $address->extract());
    }

    public function testExtractNewEntity()
    {
        $address = new Address();

        $this->assertSame([
            'city' => null,
            'lines' => [],
            'state' => null,
            'postalCode' => null,
            'country' => null,
        ], $address->extract());
    }
}
