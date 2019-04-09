<?php

declare(strict_types=1);

namespace HelpScout\Api\Tests\Reports\User;

use HelpScout\Api\Reports\User\HappinessDrilldown;
use PHPUnit\Framework\TestCase;

class HappinessDrilldownTest extends TestCase
{
    public function testConstants()
    {
        $endpoint = '/v2/reports/user/ratings';
        $fields = [
            'user',
            'start',
            'end',
            'previousStart',
            'previousEnd',
            'mailboxes',
            'tags',
            'types',
            'folders',
            'page',
            'sortFieldRatings',
            'sortOrder',
            'rating',
        ];

        $this->assertSame($endpoint, HappinessDrilldown::ENDPOINT);
        $this->assertSame($fields, HappinessDrilldown::QUERY_FIELDS);
    }
}
