<?php

declare(strict_types=1);

namespace HelpScout\Api\Tests\Reports\User;

use HelpScout\Api\Reports\User\Drilldown;
use PHPUnit\Framework\TestCase;

class DrilldownTest extends TestCase
{
    public function testConstants()
    {
        $endpoint = '/v2/reports/user/drilldown';
        $fields = [
            'user',
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
