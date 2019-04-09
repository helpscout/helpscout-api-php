<?php

declare(strict_types=1);

namespace HelpScout\Api\Reports\Happiness;

use HelpScout\Api\Reports\Report;

class Ratings extends Report
{
    public const ENDPOINT = '/v2/reports/happiness/ratings';
    public const QUERY_FIELDS = [
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
