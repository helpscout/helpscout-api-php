<?php

declare(strict_types=1);

namespace HelpScout\Api\Tests\Reports\Conversations;

use HelpScout\Api\Reports\Conversations\NewConversationDrilldown;
use PHPUnit\Framework\TestCase;

class NewConversationDrilldownTest extends TestCase
{
    public function testConstants()
    {
        $endpoint = '/v2/reports/conversations/new-drilldown';
        $fields = [
            'start',
            'end',
            'mailboxes',
            'tags',
            'types',
            'folders',
            'page',
            'rows',
        ];

        $this->assertSame($endpoint, NewConversationDrilldown::ENDPOINT);
        $this->assertSame($fields, NewConversationDrilldown::QUERY_FIELDS);
    }
}
