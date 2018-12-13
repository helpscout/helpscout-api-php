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

    /**
     * @param array           $items
     * @param HalPageMetadata $pageMetadata
     * @param HalLinks        $links
     * @param callable        $loader
     */
    public function __construct(array $items, HalPageMetadata $pageMetadata, HalLinks $links, callable $loader)
    {
        parent::__construct($items);

        $this->pageMetadata = $pageMetadata;
        $this->links = $links;
        $this->loader = $loader;
    }

    /**
     * @return int
     */
    public function getPageNumber(): int
    {
        return $this->pageMetadata->getPageNumber();
    }

    /**
     * @return int
     */
    public function getPageSize(): int
    {
        return $this->pageMetadata->getPageSize();
    }

    /**
     * @return int
     */
    public function getPageElementCount(): int
    {
        return count($this);
    }

    /**
     * @return int
     */
    public function getTotalElementCount(): int
    {
        return $this->pageMetadata->getTotalElementCount();
    }

    /**
     * @return int
     */
    public function getTotalPageCount(): int
    {
        return $this->pageMetadata->getTotalPageCount();
    }

    /**
     * @param int $number
     *
     * @return self
     */
    public function getPage(int $number): self
    {
        return $this->loadPage($this->links->expand(self::REL_PAGE, [self::PAGE_VARIABLE => $number]));
    }

    /**
     * @return self
     */
    public function getNextPage(): self
    {
        return $this->loadPage($this->links->getHref(HalLink::REL_NEXT));
    }

    /**
     * @return self
     */
    public function getPreviousPage(): self
    {
        return $this->loadPage($this->links->getHref(HalLink::REL_PREVIOUS));
    }

    /**
     * @return self
     */
    public function getFirstPage(): self
    {
        return $this->loadPage($this->links->getHref(HalLink::REL_FIRST));
    }

    /**
     * @return self
     */
    public function getLastPage(): self
    {
        return $this->loadPage($this->links->getHref(HalLink::REL_LAST));
    }

    /**
     * @param string $uri
     *
     * @return self
     */
    private function loadPage(string $uri): self
    {
        return call_user_func($this->loader, $uri);
    }
}
