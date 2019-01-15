<?php

declare(strict_types=1);

namespace HelpScout\Api\Reports;

use HelpScout\Api\Endpoint;

/**
 * Class ReportsEndpoint.
 *
 * @method array getCompanyCustomersHelpedReport(array $params = [])
 * @method array getCompanyDrilldownReport(array $params = [])
 * @method array getCompanyOverallReport(array $params = [])
 * @method array getConversationsBusyTimesReport(array $params = [])
 * @method array getConversationsDrilldownReport(array $params = [])
 * @method array getConversationsDrilldownByFieldReport(array $params = [])
 * @method array getConversationsNewConversationDrilldownReport(array $params = [])
 * @method array getConversationsNewConversationsReport(array $params = [])
 * @method array getConversationsOverallReport(array $params = [])
 * @method array getConversationsReceivedMessagesReport(array $params = [])
 * @method array getDocsOverallReport(array $params = [])
 * @method array getHappinessOverallReport(array $params = [])
 * @method array getHappinessRatingsReport(array $params = [])
 * @method array getProductivityFirstResponseTimeReport(array $params = [])
 * @method array getProductivityOverallReport(array $params = [])
 * @method array getProductivityRepliesSentReport(array $params = [])
 * @method array getProductivityResolutionTimeReport(array $params = [])
 * @method array getProductivityResolvedReport(array $params = [])
 * @method array getProductivityResponseTimeReport(array $params = [])
 * @method array getUserConversationHistoryReport(array $params = [])
 * @method array getUserCustomersHelpedReport(array $params = [])
 * @method array getUserDrilldownReport(array $params = [])
 * @method array getUserHappinessReport(array $params = [])
 * @method array getUserHappinessDrilldownReport(array $params = [])
 * @method array getUserOverallReport(array $params = [])
 * @method array getUserRepliesReport(array $params = [])
 * @method array getUserResolutionsReport(array $params = [])
 */
class ReportsEndpoint extends Endpoint
{
    /**
     * @param string $reportName
     * @param array  $params
     *
     * @return array
     */
    public function runReport(string $reportName, array $params = []): array
    {
        if (!\class_exists($reportName)) {
            throw new \InvalidArgumentException("'{$reportName}' is not a valid report");
        }

        /** @var Report $reportName */
        $report = $reportName::getInstance($params);

        return $this->restClient->getReport($report);
    }

    /**
     * @param string $name
     * @param array  $arguments
     *
     * @return array
     */
    public function __call($name, $arguments)
    {
        $report = $this->getReportName($name);

        return $this->runReport($report, $arguments[0]);
    }

    /**
     * @param string $name
     *
     * @return string
     */
    private function getReportName(string $name): string
    {
        /**
         * Incoming string is formatted as `get{reportType}{reportName}Report`
         * This method and regex will strip the `get` and `Report` values while
         * also splitting the `reportType` and `reportName` string. When recombined,
         * the fully-qualified name of the report class will be returned.
         */
        $prefix = '/(get)(Company|Conversations|Docs|Happiness|Productivity|User)(\w+)(Report)/';
        $report = \preg_replace_callback($prefix, function ($matches) {
            return __NAMESPACE__.'\\'.$matches[2].'\\'.$matches[3];
        }, $name);

        return $report;
    }
}
