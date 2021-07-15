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
    public function list(int $conversationId): PagedCollection
    {
        return $this->loadThreads(self::threadsUrl($conversationId));
    }

    public function create(int $conversationId, Thread $thread): ?int
    {
        return $this->restClient->createResource(
            $thread,
            $thread::resourceUrl($conversationId)
        );
    }

    public function hide(int $conversationId, int $threadId): void
    {
        $this->restClient->patchResource(
            Patch::replace('hidden', true),
            self::threadUrl($conversationId, $threadId)
        );
    }

    public function unhide(int $conversationId, int $threadId): void
    {
        $this->restClient->patchResource(
            Patch::replace('hidden', false),
            self::threadUrl($conversationId, $threadId)
        );
    }

    public function updateText(int $conversationId, int $threadId, string $newText): void
    {
        $this->restClient->patchResource(
            Patch::replace('text', $newText),
            self::threadUrl($conversationId, $threadId)
        );
    }

    public function getSource(int $conversationId, int $threadId): Source
    {
        return $this->loadResource(
            Source::class,
            self::threadSourceUrl($conversationId, $threadId),
            [
                'Accept' => 'application/json',
            ]
        );
    }

    private static function threadSourceUrl(int $conversationId, int $threadId): string
    {
        return sprintf('/v2/conversations/%d/threads/%d/original-source', $conversationId, $threadId);
    }

    private static function threadUrl(int $conversationId, int $threadId): string
    {
        return sprintf('/v2/conversations/%d/threads/%d', $conversationId, $threadId);
    }

    private static function threadsUrl(int $conversationId): string
    {
        return sprintf('/v2/conversations/%d/threads', $conversationId);
    }

    /**
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
