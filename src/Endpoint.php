<?php

declare(strict_types=1);

namespace HelpScout\Api;

use HelpScout\Api\Entity\PagedCollection;
use HelpScout\Api\Http\Hal\HalPagedResources;
use HelpScout\Api\Http\Hal\HalResource;
use HelpScout\Api\Http\RestClient;

abstract class Endpoint
{
    /**
     * @var RestClient
     */
    protected $restClient;

    /**
     * Endpoint constructor.
     */
    public function __construct(RestClient $restClient)
    {
        $this->restClient = $restClient;
    }

    /**
     * @return mixed
     */
    protected function loadResource(
        string $entityClass,
        string $url,
        array $headers = []
    ) {
        $resource = $this->restClient->getResource($entityClass, $url, $headers);

        return $resource->getEntity();
    }

    protected function loadPage(
        string $entityClass,
        string $rel,
        string $uri
    ): PagedCollection {
        /** @var HalPagedResources $pagedResources */
        $pagedResources = $this->restClient->getResources(
                $entityClass,
                $rel,
                $uri
            );

        $mapClosure = function (HalResource $resource) {
            return $resource->getEntity();
        };
        $nextPage = function (string $uri) use ($entityClass, $rel) {
            return $this->loadPage($entityClass, $rel, $uri);
        };

        return new PagedCollection(
            $pagedResources->map($mapClosure),
            $pagedResources->getPageMetadata(),
            $pagedResources->getLinks(),
            $nextPage
        );
    }
}
