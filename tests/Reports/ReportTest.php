<?php

declare(strict_types=1);

namespace HelpScout\Api\Tests\Reports;

use HelpScout\Api\Reports;
use PHPUnit\Framework\TestCase;

class ReportTest extends TestCase
{
    public function reportTypeProvider(): array
    {
        return [
            [Reports\Company\Overall::class],
            [Reports\Company\CustomersHelped::class],
            [Reports\Company\Drilldown::class],
            [Reports\Conversations\Overall::class],
            [Reports\Conversations\NewConversations::class],
            [Reports\Conversations\NewConversationDrilldown::class],
            [Reports\Conversations\ReceivedMessages::class],
            [Reports\Conversations\BusyTimes::class],
            [Reports\Conversations\Drilldown::class],
            [Reports\Conversations\DrilldownByField::class],
            [Reports\Docs\Overall::class],
            [Reports\Happiness\Overall::class],
            [Reports\Happiness\Ratings::class],
            [Reports\Productivity\Overall::class],
            [Reports\Productivity\FirstResponseTime::class],
            [Reports\Productivity\RepliesSent::class],
            [Reports\Productivity\ResolutionTime::class],
            [Reports\Productivity\Resolved::class],
            [Reports\Productivity\ResponseTime::class],
            [Reports\User\ConversationHistory::class],
            [Reports\User\CustomersHelped::class],
            [Reports\User\Drilldown::class],
            [Reports\User\Happiness::class],
            [Reports\User\HappinessDrilldown::class],
            [Reports\User\Overall::class],
            [Reports\User\Replies::class],
            [Reports\User\Resolutions::class],
        ];
    }

    /**
     * @dataProvider reportTypeProvider
     *
     * @param string $reportName
     */
    public function testGetInstances(string $reportName)
    {
        $now = new \DateTimeImmutable('now');
        $params = [
            'start' => $now,
        ];
        $start = $now->format(Reports\Report::DATE_FORMAT);
        $path = $reportName::ENDPOINT.'?'.\http_build_query(['start' => $start]);

        $report = $reportName::getInstance($params);

        $this->assertInstanceOf($reportName, $report);
        $this->assertSame($path, $report->getUriPath());
    }
}
