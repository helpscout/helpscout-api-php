<?php

declare(strict_types=1);

namespace HelpScout\Api\Tags;

use HelpScout\Api\Endpoint;
use HelpScout\Api\Entity\PagedCollection;

class TagsEndpoint extends Endpoint
{
    public const LIST_TAGS_URI = '/v2/tags';
    public const RESOURCE_KEY = 'tags';

    /**
     * @return PagedCollection
     */
    public function list(): PagedCollection
    {
        return $this->loadPage(
            Tag::class,
            self::RESOURCE_KEY,
            self::LIST_TAGS_URI
        );
    }
}
