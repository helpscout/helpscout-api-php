<?php

declare(strict_types=1);

namespace HelpScout\Api\Reports;

abstract class Report
{
    public const ENDPOINT = '/v2/reports';
    public const QUERY_FIELDS = [];
    public const DATE_FORMAT = 'Y-m-d\Th:m:s\Z';

    /**
     * @var ParameterBag
     */
    private $params;

    /**
     * Report constructor.
     *
     * @param ParameterBag $params
     */
    public function __construct(ParameterBag $params)
    {
        $this->params = $params;
    }

    /**
     * @return string
     */
    public function getQuery(): string
    {
        return \http_build_query($this->params->getParams());
    }

    /**
     * @return string
     */
    public function getUriPath(): string
    {
        return sprintf(
            '%s?%s',
            static::ENDPOINT,
            $this->getQuery()
        );
    }

    /**
     * @param array $params
     *
     * @return Report
     */
    public static function getInstance(array $params): Report
    {
        $fields = static::QUERY_FIELDS;

        $bag = (new ParameterBagFactory($fields, $params))->build();

        return new static($bag);
    }
}
