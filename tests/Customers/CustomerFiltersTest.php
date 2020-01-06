<?php

declare(strict_types=1);

namespace HelpScout\Api\Tests\Customers;

use DateTime;
use HelpScout\Api\Customers\CustomerFilters;
use HelpScout\Api\Exception\InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class CustomerFiltersTest extends TestCase
{
    public function testGetParamsDoesNotReturnNullValues()
    {
        $filters = new CustomerFilters();

        $this->assertSame([], $filters->getParams());
    }

    public function testGetParams()
    {
        $filters = (new CustomerFilters())
            ->inMailbox(1)
            ->byFirstName('Tom')
            ->byLastName('Graham')
            ->modifiedSince(new DateTime('2017-05-06T09:04:23+05:00'))
            ->sortField('firstName')
            ->sortOrder('asc')
            ->withQuery('query');

        $this->assertSame([
            'mailbox' => 1,
            'firstName' => 'Tom',
            'lastName' => 'Graham',
            'modifiedSince' => '2017-05-06T04:04:23Z',
            'sortField' => 'firstName',
            'sortOrder' => 'asc',
            'query' => 'query',
        ], $filters->getParams());
    }

    public function testWithInvalidMailboxId()
    {
        $this->expectException(InvalidArgumentException::class);

        (new CustomerFilters())->inMailbox(0);
    }

    /**
     * @dataProvider sortFieldProvider
     */
    public function testWithValidSortField($sortField)
    {
        $filters = (new CustomerFilters())
            ->sortField($sortField);

        $this->assertSame([
            'sortField' => $sortField,
        ], $filters->getParams());
    }

    public function sortFieldProvider(): array
    {
        return [
            ['score'],
            ['firstName'],
            ['lastName'],
            ['modifiedAt'],
        ];
    }

    public function testWithInvalidSortField()
    {
        $this->expectException(InvalidArgumentException::class);

        (new CustomerFilters())->sortField('invalid');
    }

    /**
     * @dataProvider sortOrderProvider
     */
    public function testWithValidSortOrder($sortOrder, $sortOrderParam)
    {
        $filters = (new CustomerFilters())
            ->sortOrder($sortOrder);

        $this->assertSame([
            'sortOrder' => $sortOrderParam,
        ], $filters->getParams());
    }

    public function sortOrderProvider(): array
    {
        return [
            ['asc', 'asc'],
            ['desc', 'desc'],
            ['ASC', 'asc'],
            ['DESC', 'desc'],
        ];
    }

    public function testWithInvalidSortOrder()
    {
        $this->expectException(InvalidArgumentException::class);

        (new CustomerFilters())->sortOrder('invalid');
    }
}
