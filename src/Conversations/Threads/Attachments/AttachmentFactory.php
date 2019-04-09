<?php

declare(strict_types=1);

namespace HelpScout\Api\Conversations\Threads\Attachments;

use HelpScout\Api\Exception\RuntimeException;
use HelpScout\Api\Support\Filesystem;

class AttachmentFactory
{
    /** @var Filesystem */
    protected $filesystem;

    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    public function make(string $path): Attachment
    {
        if (is_file($path) === false) {
            throw new RuntimeException('Only files may be attached');
        }

        $attachment = new Attachment();
        $attachment->setFilename(basename($path));

        $mimeType = $this->filesystem->mimeType($path);
        if (is_string($mimeType) === false) {
            throw new RuntimeException('Unable to obtain mime type for '.$path);
        }
        $attachment->setMimeType($mimeType);

        $fileContents = $this->filesystem->contents($path);
        if (is_string($fileContents) === false) {
            throw new RuntimeException('Unable to obtain file contents for '.$path);
        }
        $attachment->setData(base64_encode($fileContents));

        return $attachment;
    }
}
