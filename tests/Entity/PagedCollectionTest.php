<?php

declare(strict_types=1);

namespace HelpScout\Api\Tests\Entity;

use HelpScout\Api\Entity\PagedCollection;
use HelpScout\Api\Http\Hal\HalLink;
use HelpScout\Api\Http\Hal\HalLinks;
use HelpScout\Api\Http\Hal\HalPageMetadata;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;

class PagedCollectionTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /**
     * @var PagedCollection|MockInterface
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

    public function setUp()
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

    public function testPreviousPage()
    {
        $this->assertSame($this->loadedCollection, $this->collection->getPreviousPage());
        $this->assertSame('https://api.helpscout.com/v2/customers?page=1', $this->getLoadedUri());
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
