<?php

declare(strict_types=1);

namespace HelpScout\Api\Tags;

use HelpScout\Api\Entity\Extractable;

/**
 * This collection is only used when updating the Tags on a conversation.  It's only required because
 * we need a "tags" key populated with the collection of fields.
 */
class TagsCollection implements Extractable
{
    /** @var array */
    private $tags;

    public function extract(): array
    {
        return [
            'tags' => $this->tags,
        ];
    }

    public function getTags(): array
    {
        return $this->tags;
    }

    public function setTags(array $tags): void
    {
        $this->tags = $tags;
    }
}
