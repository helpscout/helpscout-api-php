<?php

declare(strict_types=1);

namespace HelpScout\Api\Tests\Reports;

use HelpScout\Api\Reports;
use PHPUnit\Framework\TestCase;

class ParameterBagFactoryTest extends TestCase
{
    public function testBuild()
    {
        $date = new \DateTimeImmutable('now');
        $mailboxes = [
            123,
            321,
        ];

        $officeHours = 1;
        $ratings = 'ok';

        $fields = [
            'start',
            'mailboxes',
            'officeHours',
            'ratings',
        ];
        $params = [
            'start' => $date,
            'mailboxes' => $mailboxes,
            'officeHours' => $officeHours,
            'ratings' => $ratings,
        ];

        $expected = [
            'start' => $date->format(Reports\Report::DATE_FORMAT),
            'mailboxes' => \implode(',', $mailboxes),
            'officeHours' => (bool) $officeHours,
            'ratings' => 'ok',
        ];

        $bag = (new Reports\ParameterBagFactory($fields, $params))->build();

        $this->assertSame($expected, $bag->getParams());
    }
}
