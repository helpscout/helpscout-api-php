<?php

declare(strict_types=1);

namespace HelpScout\Api\Tests\Reports\User;

use HelpScout\Api\Reports\User\Resolutions;
use PHPUnit\Framework\TestCase;

class ResolutionsTest extends TestCase
{
    public function testConstants()
    {
        $endpoint = '/v2/reports/user/resolutions';
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
            'viewBy',
        ];

        $this->assertSame($endpoint, Resolutions::ENDPOINT);
        $this->assertSame($fields, Resolutions::QUERY_FIELDS);
    }
}
