<?php

declare(strict_types=1);

namespace HelpScout\Api\Tests\Tags;

use HelpScout\Api\Tags\Tag;
use PHPUnit\Framework\TestCase;

class TagTest extends TestCase
{
    public function testHydrate()
    {
        $tag = new Tag();
        $tag->hydrate([
            'id' => 9150,
            'color' => '#929499',
            'name' => 'a vip',
            'slug' => 'a-vip',
        ]);

        $this->assertSame(9150, $tag->getId());
        $this->assertSame('#929499', $tag->getColor());
        $this->assertSame('a vip', $tag->getName());
        $this->assertSame('a-vip', $tag->getSlug());
    }

    public function testHydrateTagAsName()
    {
        $tag = new Tag();
        $tag->hydrate([
            'tag' => 'a vip',
        ]);

        $this->assertSame('a vip', $tag->getName());
    }

    public function testExtract()
    {
        $tag = new Tag();
        $tag->setId('9150');
        $tag->setColor('#929499');
        $tag->setName('a vip');
        $tag->setSlug('a-vip');
        $tag->setCreatedAt('2010-02-10T09:00:00Z');
        $tag->setUpdatedAt('2010-02-10T10:37:00Z');
        $tag->setTicketCount(2);

        $this->assertSame([
            'id' => '9150',
            'color' => '#929499',
            'name' => 'a vip',
            'slug' => 'a-vip',
            'ticketCount' => 2,
            'createdAt' => '2010-02-10T09:00:00Z',
            'updatedAt' => '2010-02-10T10:37:00Z',
        ], $tag->extract());
    }

    public function testExtractNewEntity()
    {
        $tag = new Tag();

        $this->assertSame([
            'id' => null,
            'color' => null,
            'name' => null,
            'slug' => null,
            'ticketCount' => null,
        ], $tag->extract());
    }
}
