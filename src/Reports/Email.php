<?php

declare(strict_types=1);

namespace HelpScout\Api\Reports;

class Email extends Report
{
    public const ENDPOINT = '/v2/reports/email';
    public const QUERY_FIELDS = [
        'start',
        'end',
        'previousStart',
        'previousEnd',
        'mailboxes',
        'tags',
        'folders',
        'officeHours',
    ];
}
