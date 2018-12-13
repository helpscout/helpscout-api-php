<?php

declare(strict_types=1);

namespace HelpScout\Api\Tests\Reports\Productivity;

use HelpScout\Api\Reports\Productivity\Overall;
use PHPUnit\Framework\TestCase;

class OverallTest extends TestCase
{
    public function testConstants()
    {
        $endpoint = '/v2/reports/productivity';
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
        ];

        $this->assertSame($endpoint, Overall::ENDPOINT);
        $this->assertSame($fields, Overall::QUERY_FIELDS);
    }
}
