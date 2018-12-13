<?php

declare(strict_types=1);

namespace HelpScout\Api\Tests\Reports\Happiness;

use HelpScout\Api\Reports\Happiness\Ratings;
use PHPUnit\Framework\TestCase;

class RatingsTest extends TestCase
{
    public function testConstants()
    {
        $endpoint = '/v2/reports/happiness/ratings';
        $fields = [
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

        $this->assertSame($endpoint, Ratings::ENDPOINT);
        $this->assertSame($fields, Ratings::QUERY_FIELDS);
    }
}
