<?php

declare(strict_types=1);

namespace HelpScout\Api\Tests\Conversations\Threads\Attachments;

use HelpScout\Api\Conversations\Threads\Attachments\Attachment;
use PHPUnit\Framework\TestCase;

class AttachmentTest extends TestCase
{
    public function testHydrate()
    {
        $attachment = new Attachment();
        $attachment->hydrate([
            'id' => 12,
            'fileName' => 'something.jpg',
            'mimeType' => 'image/jpeg',
            'data' => 'ZmlsZQ==',
            'width' => 132,
            'height' => 144,
            'size' => 401230,
        ]);

        $this->assertSame(12, $attachment->getId());
        $this->assertSame('something.jpg', $attachment->getFilename());
        $this->assertSame('image/jpeg', $attachment->getMimeType());
        $this->assertSame('ZmlsZQ==', $attachment->getData());
        $this->assertSame(132, $attachment->getWidth());
        $this->assertSame(144, $attachment->getHeight());
        $this->assertSame(401230, $attachment->getSize());
    }

    public function testCanHydrateFilesWithoutDimensions()
    {
        $attachment = new Attachment();
        $attachment->hydrate([
            'width' => 0,
            'height' => 0,
            'size' => 0,
        ]);

        $this->assertSame(0, $attachment->getWidth());
        $this->assertSame(0, $attachment->getHeight());
        $this->assertSame(0, $attachment->getSize());
    }

    public function testHydratesWithLowercaseFilename()
    {
        $attachment = new Attachment();
        $attachment->hydrate([
            'filename' => 'something.jpg',
        ]);

        $this->assertSame('something.jpg', $attachment->getFilename());
    }

    public function testExtract()
    {
        $attachment = new Attachment();
        $attachment->setId(12);
        $attachment->setFilename('something.jpg');
        $attachment->setMimeType('image/jpeg');
        $attachment->setData('ZmlsZQ==');
        $attachment->setWidth(132);
        $attachment->setHeight(144);
        $attachment->setSize(401230);

        $this->assertSame([
            'id' => 12,
            'fileName' => 'something.jpg',
            'mimeType' => 'image/jpeg',
            'data' => 'ZmlsZQ==',
            'width' => 132,
            'height' => 144,
            'size' => 401230,
        ], $attachment->extract());
    }

    public function testExtractNewEntity()
    {
        $this->assertSame([
            'id' => null,
            'fileName' => null,
            'mimeType' => null,
            'data' => null,
            'width' => null,
            'height' => null,
            'size' => null,
        ], (new Attachment())->extract());
    }
}
