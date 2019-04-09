<?php

declare(strict_types=1);

namespace HelpScout\Api\Reports\User;

use HelpScout\Api\Reports\Report;

class HappinessDrilldown extends Report
{
    public const ENDPOINT = '/v2/reports/user/ratings';
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
        'page',
        'sortFieldRatings',
        'sortOrder',
        'rating',
    ];
}
