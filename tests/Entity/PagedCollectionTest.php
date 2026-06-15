<?php

declare(strict_types=1);

namespace HelpScout\Api\Tests\Entity;

use HelpScout\Api\Entity\PagedCollection;
use HelpScout\Api\Http\Hal\HalLink;
use HelpScout\Api\Http\Hal\HalLinks;
use HelpScout\Api\Http\Hal\HalPageMetadata;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\LegacyMockInterface;
use PHPUnit\Framework\TestCase;

class PagedCollectionTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /**
     * @var PagedCollection|LegacyMockInterface
     */
    private $loadedCollection;

    /**
     * @var PageLoaderStub
     */
    private $pageLoader;

    /**
     * @var PagedCollection
     */
    private $collection;

    public function setUp(): void
    {
        $this->loadedCollection = Mockery::mock(PagedCollection::class);
        $this->pageLoader = new PageLoaderStub($this->loadedCollection);

        $this->collection = new PagedCollection(
            range(1, 10),
            new HalPageMetadata(2, 10, 35, 4),
            new HalLinks([
                new HalLink('page', 'https://api.helpscout.com/v2/customers{?page}', true),
                new HalLink('first', 'https://api.helpscout.com/v2/customers?page=1', false),
                new HalLink('last', 'https://api.helpscout.com/v2/customers?page=4', false),
                new HalLink('next', 'https://api.helpscout.com/v2/customers?page=3', false),
                new HalLink('previous', 'https://api.helpscout.com/v2/customers?page=1', false),
            ]),
            $this->pageLoader
        );
    }

    public function testProvidesPageInformation()
    {
        $this->assertSame(2, $this->collection->getPageNumber());
        $this->assertSame(10, $this->collection->getPageSize());
        $this->assertSame(10, $this->collection->getPageElementCount());
        $this->assertSame(35, $this->collection->getTotalElementCount());
        $this->assertSame(4, $this->collection->getTotalPageCount());
    }

    public function testGetPage()
    {
        $this->assertSame($this->loadedCollection, $this->collection->getPage(3));
        $this->assertSame('https://api.helpscout.com/v2/customers?page=3', $this->getLoadedUri());
    }

    public function testNextPage()
    {
        $this->assertSame($this->loadedCollection, $this->collection->getNextPage());
        $this->assertSame('https://api.helpscout.com/v2/customers?page=3', $this->getLoadedUri());
    }

    public function testHasNextPageReflectsLinkPresence()
    {
        $this->assertTrue($this->collection->hasNextPage());

        $lastPage = new PagedCollection(
            range(1, 5),
            new HalPageMetadata(4, 10, 35, 4),
            new HalLinks([
                new HalLink('page', 'https://api.helpscout.com/v2/customers{?page}', true),
                new HalLink('first', 'https://api.helpscout.com/v2/customers?page=1', false),
                new HalLink('last', 'https://api.helpscout.com/v2/customers?page=4', false),
                new HalLink('previous', 'https://api.helpscout.com/v2/customers?page=3', false),
            ]),
            $this->pageLoader
        );

        $this->assertFalse($lastPage->hasNextPage());
    }

    public function testHasNextPageIsFalseWhenLinkAbsentDespiteUnexhaustedPageCount()
    {
        // Page 2 of a reported 4, but the API omitted the `next` link because the result set
        // shrank underneath the pagination — the bug scenario from SDS-11533. hasNextPage()
        // must report the truth (no next) rather than trusting the stale total-page count.
        $collection = new PagedCollection(
            range(1, 10),
            new HalPageMetadata(2, 10, 35, 4),
            new HalLinks([
                new HalLink('page', 'https://api.helpscout.com/v2/customers{?page}', true),
                new HalLink('first', 'https://api.helpscout.com/v2/customers?page=1', false),
                new HalLink('previous', 'https://api.helpscout.com/v2/customers?page=1', false),
            ]),
            $this->pageLoader
        );

        $this->assertLessThan($collection->getTotalPageCount(), $collection->getPageNumber());
        $this->assertFalse($collection->hasNextPage());
    }

    public function testPreviousPage()
    {
        $this->assertSame($this->loadedCollection, $this->collection->getPreviousPage());
        $this->assertSame('https://api.helpscout.com/v2/customers?page=1', $this->getLoadedUri());
    }

    public function testHasPreviousPageReflectsLinkPresence()
    {
        $this->assertTrue($this->collection->hasPreviousPage());

        $firstPage = new PagedCollection(
            range(1, 10),
            new HalPageMetadata(1, 10, 35, 4),
            new HalLinks([
                new HalLink('page', 'https://api.helpscout.com/v2/customers{?page}', true),
                new HalLink('first', 'https://api.helpscout.com/v2/customers?page=1', false),
                new HalLink('last', 'https://api.helpscout.com/v2/customers?page=4', false),
                new HalLink('next', 'https://api.helpscout.com/v2/customers?page=2', false),
            ]),
            $this->pageLoader
        );

        $this->assertFalse($firstPage->hasPreviousPage());
    }

    public function testFirstPage()
    {
        $this->assertSame($this->loadedCollection, $this->collection->getFirstPage());
        $this->assertSame('https://api.helpscout.com/v2/customers?page=1', $this->getLoadedUri());
    }

    public function testLastPage()
    {
        $this->assertSame($this->loadedCollection, $this->collection->getLastPage());
        $this->assertSame('https://api.helpscout.com/v2/customers?page=4', $this->getLoadedUri());
    }

    private function getLoadedUri(): string
    {
        return $this->pageLoader->getCalls()[0][0];
    }
}
