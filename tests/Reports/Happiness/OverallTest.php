<?php

declare(strict_types=1);

namespace HelpScout\Api\Tests\Reports\Happiness;

use HelpScout\Api\Reports\Happiness\Overall;
use PHPUnit\Framework\TestCase;

class OverallTest extends TestCase
{
    public function testConstants()
    {
        $endpoint = '/v2/reports/happiness';
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
