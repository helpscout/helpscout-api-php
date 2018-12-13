<?php

declare(strict_types=1);

namespace HelpScout\Api\Tests\Reports\User;

use HelpScout\Api\Reports\User\Happiness;
use PHPUnit\Framework\TestCase;

class HappinessTest extends TestCase
{
    public function testConstants()
    {
        $endpoint = '/v2/reports/user/happiness';
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
        ];

        $this->assertSame($endpoint, Happiness::ENDPOINT);
        $this->assertSame($fields, Happiness::QUERY_FIELDS);
    }
}
