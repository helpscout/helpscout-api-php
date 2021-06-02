<?php

declare(strict_types=1);

namespace HelpScout\Api\Tests\Customers;

use HelpScout\Api\Customers\Entry\Property;
use PHPUnit\Framework\TestCase;

class PropertyTest extends TestCase
{
    public function testHydrate()
    {
        $property = new Property();
        $property->hydrate([
            'type' => 'text',
            'slug' => 'favorite-color',
            'name' => 'Favorite Color',
            'value' => 'Blue',
            'source' => [
                'name' => 'api',
            ],
        ]);

        $this->assertSame('text', $property->getType());
        $this->assertSame('favorite-color', $property->getSlug());
        $this->assertSame('Favorite Color', $property->getName());
        $this->assertSame('Blue', $property->getValue());
        $this->assertSame([
            'name' => 'api',
        ], $property->getSource());
    }

    public function testExtract()
    {
        $data = [
            'type' => 'text',
            'slug' => 'favorite-color',
            'name' => 'Favorite Color',
            'value' => 'Blue',
            'source' => [
                'name' => 'api',
            ],
            'operation' => 'remove',
        ];
        $property = new Property();
        $property->hydrate($data);

        $expected = [
            'op' => $property->getOperation(),
            'value' => $property->getValue(),
            'path' => '/'.$property->getSlug(),
        ];

        $this->assertSame($expected, $property->extract());
    }
}
