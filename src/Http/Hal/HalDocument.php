<?php

declare(strict_types=1);

namespace HelpScout\Api\Http\Hal;

use HelpScout\Api\Exception\InvalidArgumentException;

class HalDocument
{
    /**
     * @var array
     */
    private $data;

    /**
     * @var HalLinks
     */
    private $links;

    /**
     * @var array
     */
    private $embedded = [];

    public function __construct(array $data, HalLinks $links, array $embedded)
    {
        $this->data = $data;
        $this->links = $links;
        $this->embedded = $embedded;
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function getLinks(): HalLinks
    {
        return $this->links;
    }

    public function getEmbedded(string $rel): array
    {
        if (!$this->hasEmbedded($rel)) {
            throw new InvalidArgumentException(sprintf('The embedded resource "%s" was not found', $rel));
        }

        return $this->embedded[$rel];
    }

    /**
     * Nested embedded entities is possible (e.g. Threads always are provided with their Attachments).  Rather than
     * providing an array of HalDocuments we'll convert them to arrays of data.  This helps keep HalDocument handling
     * from being scattered throughout the SDK.
     */
    public function getEmbeddedEntities(): array
    {
        /** @var HalDocument $embeddedItemData */
        $embeddedData = [];
        // Convert HalDocument|HalDocument[] to nested arrays
        foreach ($this->embedded as $embeddedType => $embeddedItems) {
            if (is_array($embeddedItems)) {
                foreach ($embeddedItems as $embeddedItemData) {
                    $embeddedData[$embeddedType][] = $embeddedItemData->getData();
                }
            } else {
                $embeddedData[$embeddedType] = $embeddedItems->getData();
            }
        }

        return $embeddedData;
    }

    public function hasEmbedded(string $rel): bool
    {
        return array_key_exists($rel, $this->embedded);
    }
}
