<?php

declare(strict_types=1);

namespace HelpScout\Api\Reports\Company;

use HelpScout\Api\Reports\Report;

class CustomersHelped extends Report
{
    public const ENDPOINT = '/v2/reports/company/customers-helped';
    public const QUERY_FIELDS = [
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
}
