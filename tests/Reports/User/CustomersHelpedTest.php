<?php

declare(strict_types=1);

namespace HelpScout\Api\Tests\Reports\User;

use HelpScout\Api\Reports\User\CustomersHelped;
use PHPUnit\Framework\TestCase;

class CustomersHelpedTest extends TestCase
{
    public function testConstants()
    {
        $endpoint = '/v2/reports/user/customers-helped';
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

        $this->assertSame($endpoint, CustomersHelped::ENDPOINT);
        $this->assertSame($fields, CustomersHelped::QUERY_FIELDS);
    }
}
