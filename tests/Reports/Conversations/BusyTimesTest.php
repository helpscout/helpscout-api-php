<?php

declare(strict_types=1);

namespace HelpScout\Api\Tests\Reports\Conversations;

use HelpScout\Api\Reports\Conversations\BusyTimes;
use PHPUnit\Framework\TestCase;

class BusyTimesTest extends TestCase
{
    public function testConstants()
    {
        $endpoint = '/v2/reports/conversations/busy-times';
        $fields = [
            'start',
            'end',
            'previousStart',
            'previousEnd',
            'mailboxes',
            'tags',
            'types',
            'folders',
        ];

        $this->assertSame($endpoint, BusyTimes::ENDPOINT);
        $this->assertSame($fields, BusyTimes::QUERY_FIELDS);
    }
}
