<?php

declare(strict_types=1);

namespace HelpScout\Api\Tests\Entity;

use HelpScout\Api\Entity\Patch;
use PHPUnit\Framework\TestCase;

class PatchTest extends TestCase
{
    public function testExtractsAttributes()
    {
        $patch = new Patch('replace', '/something', 'else');

        $this->assertEquals([
            'op' => 'replace',
            'path' => '/something',
            'value' => 'else',
        ], $patch->extract());
    }

    public function testExtractsEnsuresSlashPrefix()
    {
        $patch = new Patch('replace', 'something', 'else');

        $this->assertEquals([
            'op' => 'replace',
            'path' => '/something',
            'value' => 'else',
        ], $patch->extract());
    }

    public function testReplaceOperation()
    {
        $path = uniqid();
        $value = uniqid();
        $patch = Patch::replace($path, $value);

        $this->assertEquals('replace', $patch->getOperation());
        $this->assertEquals($path, $patch->getPath());
        $this->assertEquals($value, $patch->getValue());
    }

    public function testMoveOperation()
    {
        $path = uniqid();
        $value = uniqid();
        $patch = Patch::move($path, $value);

        $this->assertEquals('move', $patch->getOperation());
        $this->assertEquals($path, $patch->getPath());
        $this->assertEquals($value, $patch->getValue());
    }

    public function testRemoveOperation()
    {
        $path = uniqid();
        $patch = Patch::remove($path);

        $this->assertEquals('remove', $patch->getOperation());
        $this->assertEquals($path, $patch->getPath());
    }
}
