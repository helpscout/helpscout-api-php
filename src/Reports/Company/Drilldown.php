<?php

declare(strict_types=1);

namespace HelpScout\Api\Reports\Company;

use HelpScout\Api\Reports\Report;

class Drilldown extends Report
{
    public const ENDPOINT = '/v2/reports/company/drilldown';
    public const QUERY_FIELDS = [
        'start',
        'end',
        'mailboxes',
        'tags',
        'types',
        'folders',
        'page',
        'rows',
        'range',
        'rangeId',
    ];
}
