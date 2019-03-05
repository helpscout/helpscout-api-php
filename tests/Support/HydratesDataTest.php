<?php

declare(strict_types=1);

namespace HelpScout\Api\Tests\Support;

use DateTime;
use HelpScout\Api\Customers\Entry\Address;
use HelpScout\Api\Entity\Collection;
use HelpScout\Api\Support\HydratesData;
use PHPUnit\Framework\TestCase;

class HydratesDataTest extends TestCase
{
    use HydratesData;

    public function testHydratesOneToEntity()
    {
        /** @var Address $address */
        $address = $this->hydrateOne(Address::class, [
            'city' => 'Baltimore',
        ]);

        $this->assertInstanceOf(Address::class, $address);
        $this->assertEquals('Baltimore', $address->getCity());
    }

    public function testHydratesManyToCollection()
    {
        /** @var Address[]|Collection $address */
        $addresses = $this->hydrateMany(Address::class, [
            [
                'city' => 'London',
            ],
            [
                'city' => 'Baltimore',
            ],
        ]);
        $this->assertInstanceOf(Collection::class, $addresses);

        $this->assertInstanceOf(Address::class, $addresses[0]);
        $this->assertEquals('London', $addresses[0]->getCity());

        $this->assertInstanceOf(Address::class, $addresses[1]);
        $this->assertEquals('Baltimore', $addresses[1]->getCity());
    }

    public function testTransformsDateTimeToNullWhenNull()
    {
        $this->assertNull($this->transformDateTime(null));
    }

    public function testTransformsDateTimeToDateTimeWhenNull()
    {
        $timestamp = new DateTime();
        $dateTime = $this->transformDateTime($timestamp->format('c'));

        $this->assertInstanceOf(DateTime::class, $dateTime);
    }
}
