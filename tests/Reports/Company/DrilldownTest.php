<?php

declare(strict_types=1);

namespace HelpScout\Api\Tests\Reports\Company;

use HelpScout\Api\Reports\Company\Drilldown;
use PHPUnit\Framework\TestCase;

class DrilldownTest extends TestCase
{
    public function testConstants()
    {
        $endpoint = '/v2/reports/company/drilldown';
        $fields = [
            'start',
            'end',
            'mailboxes',
            'tags',
            'types',
            'folders',
            'page',
            'rows',
            'range',
            'rangeId',
        ];

        $this->assertSame($endpoint, Drilldown::ENDPOINT);
        $this->assertSame($fields, Drilldown::QUERY_FIELDS);
    }
}
