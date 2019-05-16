<?php

declare(strict_types=1);

namespace HelpScout\Api\Tests\Reports\Conversations;

use HelpScout\Api\Reports\Conversations\BusyTimes;
use HelpScout\Api\Reports\Conversations\VolumesByChannel;
use PHPUnit\Framework\TestCase;

class VolumesByChannelTest extends TestCase
{
    public function testConstants()
    {
        $endpoint = '/v2/reports/conversations/volume-by-channel';
        $fields = [
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

        $this->assertSame($endpoint, VolumesByChannel::ENDPOINT);
        $this->assertSame($fields, VolumesByChannel::QUERY_FIELDS);
    }
}
