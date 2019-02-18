<?php

declare(strict_types=1);

namespace HelpScout\Api\Conversations\Threads;

use HelpScout\Api\Endpoint;
use HelpScout\Api\Entity\PagedCollection;
use HelpScout\Api\Entity\Patch;
use HelpScout\Api\Http\Hal\HalPagedResources;
use HelpScout\Api\Http\Hal\HalResource;

class ThreadsEndpoint extends Endpoint
{
    /**
     * @param int $conversationId
     *
     * @return PagedCollection
     */
    public function list(int $conversationId): PagedCollection
    {
        return $this->loadThreads(sprintf('/v2/conversations/%d/threads', $conversationId));
    }

    /**
     * @param int    $conversationId
     * @param Thread $thread
     *
     * @return int|null
     */
    public function create(int $conversationId, Thread $thread): ?int
    {
        return $this->restClient->createResource(
            $thread,
            $thread::resourceUrl($conversationId)
        );
    }

    /**
     * @param int    $conversationId
     * @param int    $threadId
     * @param string $newText
     */
    public function updateText(int $conversationId, int $threadId, string $newText): void
    {
        $patch = Patch::replace('text', $newText);
        $this->restClient->patchResource(
            $patch,
            sprintf('/v2/conversations/%d/threads/%d', $conversationId, $threadId)
        );
    }

    /**
     * @param string $uri
     *
     * @return Thread[]|PagedCollection
     */
    private function loadThreads(string $uri): PagedCollection
    {
        $factory = new ThreadFactory();

        /** @var HalPagedResources $threadResources */
        $threadResources = $this->restClient->getResources(function (array $data) use ($factory) {
            return $factory->make($data['type'], $data);
        }, 'threads', $uri);
        $threads = $threadResources->map(function (HalResource $threadResource) {
            return $threadResource->getEntity();
        });

        return new PagedCollection(
            $threads,
            $threadResources->getPageMetadata(),
            $threadResources->getLinks(),
            function (string $uri) {
                return $this->loadThreads($uri);
            }
        );
    }
}
