<?php

declare(strict_types=1);

namespace HelpScout\Api\Reports\Conversations;

use HelpScout\Api\Reports\Report;

class VolumesByChannel extends Report
{
    public const ENDPOINT = '/v2/reports/conversations/volume-by-channel';
    public const QUERY_FIELDS = [
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
