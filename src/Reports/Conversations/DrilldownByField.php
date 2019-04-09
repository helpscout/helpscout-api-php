<?php

declare(strict_types=1);

namespace HelpScout\Api\Reports\Conversations;

use HelpScout\Api\Reports\Report;

class DrilldownByField extends Report
{
    public const ENDPOINT = '/v2/reports/conversations/fields-drilldown';
    public const QUERY_FIELDS = [
        'start',
        'end',
        'field',
        'fieldid',
        'mailboxes',
        'tags',
        'types',
        'folders',
        'page',
        'rows',
    ];
}
