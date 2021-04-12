<?php

declare(strict_types=1);

namespace HelpScout\Api\Chats;

use HelpScout\Api\Endpoint;
// use HelpScout\Api\Entity\Collection;
// use HelpScout\Api\Entity\PagedCollection;
// use HelpScout\Api\Entity\Patch;
// use HelpScout\Api\Exception\ValidationErrorException;
// use HelpScout\Api\Http\Hal\HalPagedResources;
// use HelpScout\Api\Http\Hal\HalResource;
// use HelpScout\Api\Tags\TagsCollection;

class ChatsEndpoint extends Endpoint
{
    public const CHAT_URI = '/chat/v1/%s';
    public const EVENTS_URI = '/chat/v1/%s/events';
    public const EVENTS_RESOURCE_KEY = 'events';

    public function get(string $id): Chat
    {
        return $this->loadResource(
            Chat::class,
            sprintf(self::CHAT_URI, $id)
        );
    }

    public function events(string $id)
    {
        return $this->loadPage(
            Event::class,
            self::EVENTS_RESOURCE_KEY,
            sprintf(self::EVENTS_URI, $id)
        );
    }
}
