<?php

declare(strict_types=1);

namespace HelpScout\Api\Entity;

use Closure;
use HelpScout\Api\Http\Hal\HalResource;
use HelpScout\Api\Http\RestClient;

abstract class LinkedEntityLoader
{
    /**
     * @var RestClient
     */
    private $restClient;

    /**
     * @var HalResource
     */
    private $resource;

    /**
     * @var array
     */
    private $links;

    public function __construct(RestClient $restClient, HalResource $resource, array $links)
    {
        $this->restClient = $restClient;
        $this->resource = $resource;
        $this->links = $links;
    }

    abstract public function load();

    protected function shouldLoadResource(string $rel): bool
    {
        return in_array($rel, $this->links, true) && $this->resource->getLinks()->has($rel);
    }

    /**
     * @param Closure|string $entityClass
     *
     * @return mixed
     */
    protected function loadResource($entityClass, string $rel)
    {
        $uri = $this->resource->getLinks()->getHref($rel);

        return $this->restClient->getResource($entityClass, $uri)->getEntity();
    }

    /**
     * @param Closure|string $entityClass
     */
    protected function loadResources($entityClass, string $rel): Collection
    {
        $uri = $this->resource->getLinks()->getHref($rel);
        $resources = $this->restClient->getResources($entityClass, $rel, $uri);

        $entities = $resources->map(function (HalResource $resource) {
            return $resource->getEntity();
        });

        return new Collection($entities);
    }

    /**
     * @return mixed
     */
    protected function getEntity()
    {
        return $this->resource->getEntity();
    }
}
