<?php

declare(strict_types=1);

namespace HelpScout\Api\Tests\Reports;

use HelpScout\Api\Tests\ApiClientIntegrationTestCase;
use HelpScout\Api\Tests\Payloads\ReportPayloads;

class ReportsEndpointTest extends ApiClientIntegrationTestCase
{
    public function callReportProvider(): array
    {
        return [
            ['getCompanyOverallReport', 'https://api.helpscout.net/v2/reports/company'],
        ];
    }

    /**
     * @dataProvider callReportProvider()
     *
     * @param string $name
     * @param string $url
     */
    public function testCall(string $name, string $url)
    {
        $data = ReportPayloads::getReportData($name);

        $this->stubResponse($this->getResponse(
            200,
            $data
        ));

        $this->client->reports()->{$name}(['params']);
        $this->verifySingleRequest($url);
    }
}
