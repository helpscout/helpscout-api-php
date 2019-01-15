<?php

declare(strict_types=1);

namespace HelpScout\Api\Conversations;

class PhoneConversation extends Conversation
{
    public function __construct()
    {
        parent::__construct();
        $this->setType(static::TYPE_PHONE);
    }
}
