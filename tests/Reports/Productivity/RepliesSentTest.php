<?php

declare(strict_types=1);

namespace HelpScout\Api\Tests\Reports\Productivity;

use HelpScout\Api\Reports\Productivity\RepliesSent;
use PHPUnit\Framework\TestCase;

class RepliesSentTest extends TestCase
{
    public function testConstants()
    {
        $endpoint = '/v2/reports/productivity/replies-sent';
        $fields = [
            'start',
            'end',
            'previousStart',
            'previousEnd',
            'mailboxes',
            'tags',
            'types',
            'folders',
            'officeHours',
            'viewBy',
        ];

        $this->assertSame($endpoint, RepliesSent::ENDPOINT);
        $this->assertSame($fields, RepliesSent::QUERY_FIELDS);
    }
}
