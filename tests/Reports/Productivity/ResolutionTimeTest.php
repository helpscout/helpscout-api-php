<?php

declare(strict_types=1);

namespace HelpScout\Api\Tests\Reports\Productivity;

use HelpScout\Api\Reports\Productivity\ResolutionTime;
use PHPUnit\Framework\TestCase;

class ResolutionTimeTest extends TestCase
{
    public function testConstants()
    {
        $endpoint = '/v2/reports/productivity/resolution-time';
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

        $this->assertSame($endpoint, ResolutionTime::ENDPOINT);
        $this->assertSame($fields, ResolutionTime::QUERY_FIELDS);
    }
}
