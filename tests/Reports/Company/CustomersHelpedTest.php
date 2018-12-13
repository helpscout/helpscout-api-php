<?php

declare(strict_types=1);

namespace HelpScout\Api\Tests\Reports\Company;

use HelpScout\Api\Reports\Company\CustomersHelped;
use PHPUnit\Framework\TestCase;

class CustomersHelpedTest extends TestCase
{
    public function testConstants()
    {
        $endpoint = '/v2/reports/company/customers-helped';
        $fields = [
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
