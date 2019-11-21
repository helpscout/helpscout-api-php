<?php

declare(strict_types=1);

namespace HelpScout\Api\Http\Hal;

class HalResources
{
    /**
     * @var HalResource[]
     */
    private $resources;

    /**
     * @var HalLinks
     */
    private $links;

    /**
     * @param HalResource[] $resources
     */
    public function __construct(array $resources, HalLinks $links)
    {
        $this->resources = $resources;
        $this->links = $links;
    }

    public function map(callable $callable): array
    {
        return array_map($callable, $this->resources);
    }

    public function getLinks(): HalLinks
    {
        return $this->links;
    }
}
