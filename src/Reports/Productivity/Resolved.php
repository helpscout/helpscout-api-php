<?php

declare(strict_types=1);

namespace HelpScout\Api\Reports\Productivity;

use HelpScout\Api\Reports\Report;

class Resolved extends Report
{
    public const ENDPOINT = '/v2/reports/productivity/resolved';
    public const QUERY_FIELDS = [
        'start',
        'end',
        'previousStart',
        'previousEnd',
        'mailboxes',
        'tags',
        'types',
        'folders',
        'officeHours',
        'viewBy',
    ];
}
