<?php

declare(strict_types=1);

namespace HelpScout\Api\Tests\Reports\Productivity;

use HelpScout\Api\Reports\Productivity\ResponseTime;
use PHPUnit\Framework\TestCase;

class ResponseTimeTest extends TestCase
{
    public function testConstants()
    {
        $endpoint = '/v2/reports/productivity/response-time';
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

        $this->assertSame($endpoint, ResponseTime::ENDPOINT);
        $this->assertSame($fields, ResponseTime::QUERY_FIELDS);
    }
}
