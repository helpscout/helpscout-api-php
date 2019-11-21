<?php

declare(strict_types=1);

namespace HelpScout\Api\Http\Hal;

class HalPagedResources extends HalResources
{
    /**
     * @var HalPageMetadata
     */
    private $pageMetadata;

    public function __construct(array $resources, HalLinks $links, HalPageMetadata $pageMetadata)
    {
        parent::__construct($resources, $links);

        $this->pageMetadata = $pageMetadata;
    }

    public function getPageMetadata(): HalPageMetadata
    {
        return $this->pageMetadata;
    }
}
