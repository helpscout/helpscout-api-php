<?php

declare(strict_types=1);

namespace HelpScout\Api\Conversations\Threads;

use HelpScout\Api\Conversations\Threads\Support\HasUser;

class NoteThread extends Thread
{
    use HasUser;

    public const TYPE = 'note';

    public static function resourceUrl(int $conversationId): string
    {
        return sprintf('/v2/conversations/%d/notes', $conversationId);
    }

    public function getType(): ?string
    {
        return self::TYPE;
    }

    public function setStatus(?string $status): NoteThread
    {
        $this->status = $status;

        return $this;
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
