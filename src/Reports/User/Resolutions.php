<?php

declare(strict_types=1);

namespace HelpScout\Api\Reports\User;

use HelpScout\Api\Reports\Report;

class Resolutions extends Report
{
    public const ENDPOINT = '/v2/reports/user/resolutions';
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
