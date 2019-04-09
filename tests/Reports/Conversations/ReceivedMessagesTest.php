<?php

declare(strict_types=1);

namespace HelpScout\Api\Tests\Reports\Conversations;

use HelpScout\Api\Reports\Conversations\ReceivedMessages;
use PHPUnit\Framework\TestCase;

class ReceivedMessagesTest extends TestCase
{
    public function testConstants()
    {
        $endpoint = '/v2/reports/conversations/received-messages';
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

        $this->assertSame($endpoint, ReceivedMessages::ENDPOINT);
        $this->assertSame($fields, ReceivedMessages::QUERY_FIELDS);
    }
}
