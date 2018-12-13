<?php

declare(strict_types=1);

namespace HelpScout\Api\Tests\Reports\Conversations;

use HelpScout\Api\Reports\Conversations\DrilldownByField;
use PHPUnit\Framework\TestCase;

class DrilldownByFieldTest extends TestCase
{
    public function testConstants()
    {
        $endpoint = '/v2/reports/conversations/fields-drilldown';
        $fields = [
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

        $this->assertSame($endpoint, DrilldownByField::ENDPOINT);
        $this->assertSame($fields, DrilldownByField::QUERY_FIELDS);
    }
}
