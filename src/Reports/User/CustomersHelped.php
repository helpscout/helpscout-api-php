<?php

declare(strict_types=1);

namespace HelpScout\Api\Reports\User;

use HelpScout\Api\Reports\Report;

class CustomersHelped extends Report
{
    public const ENDPOINT = '/v2/reports/user/customers-helped';
    public const QUERY_FIELDS = [
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
}
