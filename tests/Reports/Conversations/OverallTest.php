<?php

declare(strict_types=1);

namespace HelpScout\Api\Tests\Reports\Conversations;

use HelpScout\Api\Reports\Conversations\Overall;
use PHPUnit\Framework\TestCase;

class OverallTest extends TestCase
{
    public function testConstants()
    {
        $endpoint = '/v2/reports/conversations';
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

        $this->assertSame($endpoint, Overall::ENDPOINT);
        $this->assertSame($fields, Overall::QUERY_FIELDS);
    }
}
