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
     * @param HalLinks      $links
     */
    public function __construct(array $resources, HalLinks $links)
    {
        $this->resources = $resources;
        $this->links = $links;
    }

    /**
     * @param callable $callable
     *
     * @return array
     */
    public function map(callable $callable): array
    {
        return array_map($callable, $this->resources);
    }

    /**
     * @return HalLinks
     */
    public function getLinks(): HalLinks
    {
        return $this->links;
    }
}
