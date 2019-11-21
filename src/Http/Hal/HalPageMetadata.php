<?php

declare(strict_types=1);

namespace HelpScout\Api\Http\Hal;

class HalPageMetadata
{
    /**
     * @var int
     */
    private $pageNumber;

    /**
     * @var int
     */
    private $pageSize;

    /**
     * @var int
     */
    private $totalElementCount;

    /**
     * @var int
     */
    private $totalPageCount;

    public function __construct(int $pageNumber, int $pageSize, int $totalElementCount, int $totalPageCount)
    {
        $this->pageNumber = $pageNumber;
        $this->pageSize = $pageSize;
        $this->totalElementCount = $totalElementCount;
        $this->totalPageCount = $totalPageCount;
    }

    public function getPageNumber(): int
    {
        return $this->pageNumber;
    }

    public function getPageSize(): int
    {
        return $this->pageSize;
    }

    public function getTotalElementCount(): int
    {
        return $this->totalElementCount;
    }

    public function getTotalPageCount(): int
    {
        return $this->totalPageCount;
    }
}
