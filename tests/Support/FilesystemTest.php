<?php

declare(strict_types=1);

namespace HelpScout\Api\Tests\Support;

use HelpScout\Api\Support\Filesystem;
use PHPUnit\Framework\TestCase;

class FilesystemTest extends TestCase
{
    public function testProvidesMimeType()
    {
        $filesystem = new Filesystem();
        $this->assertSame('text/x-php', $filesystem->mimeType(__FILE__));
    }

    public function testProvidesFileContents()
    {
        $filesystem = new Filesystem();
        $this->assertStringStartsWith('<?php', $filesystem->contents(__FILE__));
    }
}
