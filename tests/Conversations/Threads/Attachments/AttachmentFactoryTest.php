<?php

declare(strict_types=1);

namespace HelpScout\Api\Tests\Conversations\Threads\Attachments;

use HelpScout\Api\Conversations\Threads\Attachments\AttachmentFactory;
use HelpScout\Api\Exception\RuntimeException;
use HelpScout\Api\Support\Filesystem;
use PHPUnit\Framework\TestCase;

class AttachmentFactoryTest extends TestCase
{
    /** @var AttachmentFactory */
    private $factory;

    /** @var \PHPUnit_Framework_MockObject_MockObject|Filesystem */
    private $filesystem;

    public function setUp()
    {
        parent::setUp();

        $this->filesystem = $this->createMock(Filesystem::class);
        $this->factory = new AttachmentFactory($this->filesystem);
    }

    public function testRejectsDirectoryPaths()
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Only files may be attached');

        $this->factory->make(__DIR__);
    }

    public function testAcceptsFiles()
    {
        $this->filesystem->method('mimeType')
            ->willReturn(mime_content_type(__FILE__));

        $this->filesystem->method('contents')
            ->willReturn(file_get_contents(__FILE__));

        $attachment = $this->factory->make(__FILE__);

        $this->assertSame(basename(__FILE__), $attachment->getFilename());
        $this->assertSame('text/x-php', $attachment->getMimeType());
        $this->assertSame(base64_encode(file_get_contents(__FILE__)), $attachment->getData());
    }

    public function testFailsWhenUnableToObtainMimeType()
    {
        $path = __FILE__;
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Unable to obtain mime type for '.$path);

        $this->filesystem->method('mimeType')
            ->willReturn(false);

        $this->factory->make($path);
    }

    public function testFailsWhenUnableToObtainContents()
    {
        $path = __FILE__;
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Unable to obtain file contents for '.$path);

        $this->filesystem->method('mimeType')
            ->willReturn('text/x-php');
        $this->filesystem->method('contents')
            ->willReturn(false);

        $this->factory->make($path);
    }
}
