<?php

declare(strict_types=1);

namespace HelpScout\Api\Conversations;

class Status
{
    /**
     * We're using the verbiage "ANY" instead of "ALL" because a conversation can only have a single status, not multiple.
     */
    const ANY = 'all';
    const ACTIVE = 'active';
    const CLOSED = 'closed';
    const OPEN = 'open';
    const PENDING = 'pending';
    const SPAM = 'spam';
}
