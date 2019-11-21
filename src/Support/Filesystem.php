<?php

declare(strict_types=1);

namespace HelpScout\Api\Support;

/**
 * Wrapping basic filesystem methods in an object for easy mocking.
 */
class Filesystem
{
    /**
     * @return bool|string
     */
    public function contents(string $path)
    {
        return file_get_contents($path);
    }

    /**
     * @return bool|string
     */
    public function mimeType(string $path)
    {
        return mime_content_type($path);
    }
}
