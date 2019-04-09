<?php

declare(strict_types=1);

namespace HelpScout\Api\Conversations\Threads;

use HelpScout\Api\Conversations\Threads\Support\HasUser;

class NoteThread extends Thread
{
    public const TYPE = 'note';

    use HasUser;

    public static function resourceUrl(int $conversationId): string
    {
        return sprintf('/v2/conversations/%d/notes', $conversationId);
    }

    public function extract(): array
    {
        $data = parent::extract();
        $data['type'] = self::TYPE;

        // When creating threads "user" is expected to be numeric rather
        // than an object with an "id" property
        if ($this->userId > 0) {
            $data['user'] = $this->userId;
        }

        return $data;
    }
}
