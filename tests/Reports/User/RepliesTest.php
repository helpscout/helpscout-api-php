<?php

declare(strict_types=1);

namespace HelpScout\Api\Tests\Reports\User;

use HelpScout\Api\Reports\User\Replies;
use PHPUnit\Framework\TestCase;

class RepliesTest extends TestCase
{
    public function testConstants()
    {
        $endpoint = '/v2/reports/user/replies';
        $fields = [
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

        $this->assertSame($endpoint, Replies::ENDPOINT);
        $this->assertSame($fields, Replies::QUERY_FIELDS);
    }
}
