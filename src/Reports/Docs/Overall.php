<?php

declare(strict_types=1);

namespace HelpScout\Api\Reports\Docs;

use HelpScout\Api\Reports\Report;

class Overall extends Report
{
    public const ENDPOINT = '/v2/reports/docs';
    public const QUERY_FIELDS = [
        'start',
        'end',
        'previousStart',
        'previousEnd',
        'sites',
    ];
}
