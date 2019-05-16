<?php

declare(strict_types=1);

namespace HelpScout\Api\Tests\Reports\Conversations;

use HelpScout\Api\Reports\Chat;
use PHPUnit\Framework\TestCase;

class ChatTest extends TestCase
{
    public function testConstants()
    {
        $endpoint = '/v2/reports/chat';
        $fields = [
            'start',
            'end',
            'previousStart',
            'previousEnd',
            'mailboxes',
            'tags',
            'folders',
            'officeHours',
        ];

        $this->assertSame($endpoint, Chat::ENDPOINT);
        $this->assertSame($fields, Chat::QUERY_FIELDS);
    }
}
