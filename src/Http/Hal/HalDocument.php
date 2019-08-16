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

    public function hasLinks(): bool
    {
        return $this->links->size() > 0;
    }

    /**
     * @return HalDocument|HalDocument[]
     */
    public function getEmbedded(string $rel)
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
     *
     * Links was a bit of an afterthought in this context so there's a bit of extra logic here to handle appending
     * links to the data that is passed to the hydrate method.  Part of the reason this approach was used is to prevent
     * breaking changes to the hydrate() signature of entities so it can continue to accept an array rather than an
     * object.
     */
    public function getEmbeddedEntities(): array
    {
        /** @var HalDocument $embeddedItemData */
        $embeddedData = [];
        // Convert HalDocument|HalDocument[] to nested arrays
        foreach ($this->embedded as $embeddedType => $embeddedItems) {
            if (is_array($embeddedItems)) {
                /** @var HalDocument $embeddedItemData */
                foreach ($embeddedItems as $embeddedItemData) {
                    $data = $embeddedItemData->getData();
                    if ($embeddedItemData->hasLinks()) {
                        $data[HalDeserializer::LINKS] = $embeddedItemData->getLinks();
                    }
                    $embeddedData[$embeddedType][] = $data;
                }
            } else {
                /** @var HalDocument $embeddedItems */
                $data = $embeddedItems->getData();
                if ($embeddedItems->hasLinks()) {
                    $data[HalDeserializer::LINKS] = $embeddedItems->getLinks();
                }
                $embeddedData[$embeddedType] = $data;
            }
        }

        return $embeddedData;
    }

    public function hasEmbedded(string $rel): bool
    {
        if (!array_key_exists($rel, $this->embedded)) {
            return false;
        }

        // Make sure we actually have data within this embedded entity and it's not empty
        if ($this->embedded[$rel] instanceof HalDocument && count($this->embedded[$rel]->getData()) > 0) {
            return true;
        }

        // It also may be a collection of HalDocuments
        if (is_array($this->embedded[$rel]) && count($this->embedded[$rel]) > 0) {
            return true;
        }

        return false;
    }
}
