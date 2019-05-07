<?php

declare(strict_types=1);

namespace HelpScout\Api\Tests\Entity;

use HelpScout\Api\Customers\Customer;
use HelpScout\Api\Entity\Collection;
use HelpScout\Api\Exception\RuntimeException;
use PHPUnit\Framework\TestCase;

class CollectionTest extends TestCase
{
    public function testIterable()
    {
        $items = [1, 2, 3];
        $collection = new Collection($items);

        $this->assertSame($items, iterator_to_array($collection));
    }

    public function testToArray()
    {
        $items = [1, 2, 3];
        $collection = new Collection($items);

        $this->assertSame($items, $collection->toArray());
    }

    public function testArrayAccess()
    {
        $items = [1, 2, 3];
        $collection = new Collection($items);

        $this->assertSame(1, $collection[0]);
        $this->assertSame(2, $collection[1]);
        $this->assertSame(3, $collection[2]);

        $this->assertTrue(isset($collection[1]));
        $this->assertFalse(isset($collection[5]));

        $collection[3] = 4;
        $this->assertSame(4, $collection[3]);

        unset($collection[1]);
        $this->assertFalse(isset($collection[1]));
    }

    public function testCount()
    {
        $items = [1, 2, 3];
        $collection = new Collection($items);

        $this->assertCount(3, $collection);
    }

    public function testExtractable()
    {
        $items = [
            (new Customer())->setId(123),
        ];
        $collection = new Collection($items);
        $extracted = $collection->extract();

        $this->assertSame(123, $extracted[0]['id']);
    }

    public function testFailsExtractionWhenNotExtractable()
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Entity is not extractable');

        $items = [
            new \stdClass(),
        ];
        $collection = new Collection($items);
        $collection->extract();
    }
}
