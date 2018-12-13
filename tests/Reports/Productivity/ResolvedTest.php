<?php

declare(strict_types=1);

namespace HelpScout\Api\Tests\Reports\Productivity;

use HelpScout\Api\Reports\Productivity\Resolved;
use PHPUnit\Framework\TestCase;

class ResolvedTest extends TestCase
{
    public function testConstants()
    {
        $endpoint = '/v2/reports/productivity/resolved';
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

        $this->assertSame($endpoint, Resolved::ENDPOINT);
        $this->assertSame($fields, Resolved::QUERY_FIELDS);
    }
}
