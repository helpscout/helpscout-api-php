<?php

declare(strict_types=1);

namespace HelpScout\Api\Tests\Reports\Docs;

use HelpScout\Api\Reports\Docs\Overall;
use PHPUnit\Framework\TestCase;

class OverallTest extends TestCase
{
    public function testConstants()
    {
        $endpoint = '/v2/reports/docs';
        $fields = [
            'start',
            'end',
            'previousStart',
            'previousEnd',
            'sites',
        ];

        $this->assertSame($endpoint, Overall::ENDPOINT);
        $this->assertSame($fields, Overall::QUERY_FIELDS);
    }
}
