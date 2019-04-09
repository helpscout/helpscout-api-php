<?php

declare(strict_types=1);

namespace HelpScout\Api\Tests\Reports\User;

use HelpScout\Api\Reports\User\ConversationHistory;
use PHPUnit\Framework\TestCase;

class ConversationHistoryTest extends TestCase
{
    public function testConstants()
    {
        $endpoint = '/v2/reports/user/conversation-history';
        $fields = [
            'user',
            'start',
            'end',
            'previousStart',
            'previousEnd',
            'status',
            'mailboxes',
            'tags',
            'types',
            'folders',
            'officeHours',
            'page',
            'sortField',
            'sortOrder',
        ];

        $this->assertSame($endpoint, ConversationHistory::ENDPOINT);
        $this->assertSame($fields, ConversationHistory::QUERY_FIELDS);
    }
}
