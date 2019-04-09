<?php

declare(strict_types=1);

namespace HelpScout\Api\Reports\Conversations;

use HelpScout\Api\Reports\Report;

class Overall extends Report
{
    public const ENDPOINT = '/v2/reports/conversations';
    public const QUERY_FIELDS = [
        'start',
        'end',
        'previousStart',
        'previousEnd',
        'mailboxes',
        'tags',
        'types',
        'folders',
    ];
}
