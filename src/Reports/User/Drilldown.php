<?php

declare(strict_types=1);

namespace HelpScout\Api\Reports\User;

use HelpScout\Api\Reports\Report;

class Drilldown extends Report
{
    public const ENDPOINT = '/v2/reports/user/drilldown';
    public const QUERY_FIELDS = [
        'user',
        'start',
        'end',
        'mailboxes',
        'tags',
        'types',
        'folders',
        'page',
        'rows',
    ];
}
