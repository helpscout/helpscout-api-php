<?php

declare(strict_types=1);

namespace HelpScout\Api\Tests\Reports\Conversations;

use HelpScout\Api\Reports\Conversations\NewConversations;
use PHPUnit\Framework\TestCase;

class NewConversationsTest extends TestCase
{
    public function testConstants()
    {
        $endpoint = '/v2/reports/conversations/new';
        $fields = [
            'start',
            'end',
            'previousStart',
            'previousEnd',
            'mailboxes',
            'tags',
            'types',
            'folders',
            'viewBy',
        ];

        $this->assertSame($endpoint, NewConversations::ENDPOINT);
        $this->assertSame($fields, NewConversations::QUERY_FIELDS);
    }
}
