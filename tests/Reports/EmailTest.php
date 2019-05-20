<?php

declare(strict_types=1);

namespace HelpScout\Api\Tests\Reports;

use HelpScout\Api\Reports\Email;
use PHPUnit\Framework\TestCase;

class EmailTest extends TestCase
{
    public function testConstants()
    {
        $endpoint = '/v2/reports/email';
        $fields = [
            'start',
            'end',
            'previousStart',
            'previousEnd',
            'mailboxes',
            'tags',
            'folders',
            'officeHours',
        ];

        $this->assertSame($endpoint, Email::ENDPOINT);
        $this->assertSame($fields, Email::QUERY_FIELDS);
    }
}
