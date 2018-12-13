<?php

declare(strict_types=1);

namespace HelpScout\Api\Reports\User;

use HelpScout\Api\Reports\Report;

class ConversationHistory extends Report
{
    public const ENDPOINT = '/v2/reports/user/conversation-history';
    public const QUERY_FIELDS = [
        'user',
        'start',
        'end',
        'previousStart',
        'previousEnd',
        'status',
        'mailboxes',
        'tags',
        'types',
        'folders',
        'officeHours',
        'page',
        'sortField',
        'sortOrder',
    ];
}
