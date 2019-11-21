<?php

declare(strict_types=1);

namespace HelpScout\Api\Entity;

use HelpScout\Api\Http\Hal\HalLink;
use HelpScout\Api\Http\Hal\HalLinks;
use HelpScout\Api\Http\Hal\HalPageMetadata;

class PagedCollection extends Collection
{
    /**
     * The link name for the templated page URI.
     */
    const REL_PAGE = 'page';

    /**
     * The variable name for the page number in the templated URI.
     */
    const PAGE_VARIABLE = 'page';

    /**
     * @var HalPageMetadata
     */
    private $pageMetadata;

    /**
     * @var HalLinks
     */
    private $links;

    /**
     * @var callable
     */
    private $loader;

    public function __construct(array $items, HalPageMetadata $pageMetadata, HalLinks $links, callable $loader)
    {
        parent::__construct($items);

        $this->pageMetadata = $pageMetadata;
        $this->links = $links;
        $this->loader = $loader;
    }

    public function getPageNumber(): int
    {
        return $this->pageMetadata->getPageNumber();
    }

    public function getPageSize(): int
    {
        return $this->pageMetadata->getPageSize();
    }

    public function getPageElementCount(): int
    {
        return count($this);
    }

    public function getTotalElementCount(): int
    {
        return $this->pageMetadata->getTotalElementCount();
    }

    public function getTotalPageCount(): int
    {
        return $this->pageMetadata->getTotalPageCount();
    }

    public function getPage(int $number): self
    {
        return $this->loadPage($this->links->expand(self::REL_PAGE, [self::PAGE_VARIABLE => $number]));
    }

    public function getNextPage(): self
    {
        return $this->loadPage($this->links->getHref(HalLink::REL_NEXT));
    }

    public function getPreviousPage(): self
    {
        return $this->loadPage($this->links->getHref(HalLink::REL_PREVIOUS));
    }

    public function getFirstPage(): self
    {
        return $this->loadPage($this->links->getHref(HalLink::REL_FIRST));
    }

    public function getLastPage(): self
    {
        return $this->loadPage($this->links->getHref(HalLink::REL_LAST));
    }

    private function loadPage(string $uri): self
    {
        return call_user_func($this->loader, $uri);
    }
}
