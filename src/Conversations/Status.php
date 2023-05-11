<?php

declare(strict_types=1);

namespace HelpScout\Api\Conversations;

class Status
{
    /**
     * We're using the verbiage "ANY" instead of "ALL" because a conversation can only have a single status, not multiple.
     */
    public const ANY = 'all';
    public const ACTIVE = 'active';
    public const CLOSED = 'closed';
    public const OPEN = 'open';
    public const PENDING = 'pending';
    public const SPAM = 'spam';
}
