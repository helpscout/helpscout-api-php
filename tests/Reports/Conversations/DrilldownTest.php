<?php

declare(strict_types=1);

namespace HelpScout\Api\Tests\Reports\Conversations;

use HelpScout\Api\Reports\Conversations\Drilldown;
use PHPUnit\Framework\TestCase;

class DrilldownTest extends TestCase
{
    public function testConstants()
    {
        $endpoint = '/v2/reports/conversations/drilldown';
        $fields = [
            'start',
            'end',
            'mailboxes',
            'tags',
            'types',
            'folders',
            'page',
            'rows',
        ];

        $this->assertSame($endpoint, Drilldown::ENDPOINT);
        $this->assertSame($fields, Drilldown::QUERY_FIELDS);
    }
}
