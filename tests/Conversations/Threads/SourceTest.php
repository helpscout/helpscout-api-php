<?php

declare(strict_types=1);

namespace HelpScout\Api\Tests\Conversations\Threads;

use HelpScout\Api\Conversations\Threads\Source;
use PHPUnit\Framework\TestCase;

class SourceTest extends TestCase
{
    public function testHydrate()
    {
        $original = 'original email text';
        $source = new Source();
        $source->hydrate([
            'original' => $original,
        ]);

        $this->assertSame($original, $source->getOriginal());
    }

    public function testExtract()
    {
        $original = 'original email text';
        $source = new Source();
        $source->setOriginal($original);

        $extracted = $source->extract();

        $this->assertEquals($original, $extracted['original']);
    }

    public function testSetAndGetOriginal()
    {
        $source = new Source();
        $this->assertNull($source->getOriginal());
        $original = 'original email text';

        $source->setOriginal($original);

        $this->assertEquals($original, $source->getOriginal());
    }
}
