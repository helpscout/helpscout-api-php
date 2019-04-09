<?php

declare(strict_types=1);

namespace HelpScout\Api\Tests\Reports\Productivity;

use HelpScout\Api\Reports\Productivity\FirstResponseTime;
use PHPUnit\Framework\TestCase;

class FirstResponseTimeTest extends TestCase
{
    public function testConstants()
    {
        $endpoint = '/v2/reports/productivity/first-response-time';
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

        $this->assertSame($endpoint, FirstResponseTime::ENDPOINT);
        $this->assertSame($fields, FirstResponseTime::QUERY_FIELDS);
    }
}
