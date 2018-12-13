<?php

declare(strict_types=1);

namespace HelpScout\Api\Reports;

class ParameterBag
{
    /**
     * @var array
     */
    private $params;

    /**
     * ParameterBag constructor.
     *
     * @param array $params
     */
    public function __construct(array $params)
    {
        $this->params = $params;
    }

    /**
     * @return array
     */
    public function getParams(): array
    {
        return $this->params;
    }
}
