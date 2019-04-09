<?php

declare(strict_types=1);

namespace HelpScout\Api\Tests\Payloads;

class AttachmentPayloads
{
    public static function getAttachmentData(int $id): string
    {
        return json_encode([
            'data' => 'ZmlsZQ==',
        ]);
    }
}
