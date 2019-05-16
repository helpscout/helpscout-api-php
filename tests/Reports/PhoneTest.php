<?php

declare(strict_types=1);

namespace HelpScout\Api\Tests\Reports\Conversations;

use HelpScout\Api\Reports\Phone;
use PHPUnit\Framework\TestCase;

class PhoneTest extends TestCase
{
    public function testConstants()
    {
        $endpoint = '/v2/reports/phone';
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

        $this->assertSame($endpoint, Phone::ENDPOINT);
        $this->assertSame($fields, Phone::QUERY_FIELDS);
    }
}
