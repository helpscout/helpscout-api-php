<?php

declare(strict_types=1);

namespace HelpScout\Api\Tests\Conversations;

use DateTime;
use HelpScout\Api\Conversations\ConversationFilters;
use HelpScout\Api\Conversations\Status;
use HelpScout\Api\Exception\InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class ConversationFiltersTest extends TestCase
{
    public function testGetParamsDoesNotReturnNullValues()
    {
        $filters = new ConversationFilters();

        $this->assertSame([], $filters->getParams());
    }

    public function testGetParams()
    {
        $filters = (new ConversationFilters())
            ->withMailbox(1)
            ->withFolder(13)
            ->withStatus(Status::ANY)
            ->withTag('testing')
            ->withAssignedTo(1771)
            ->withModifiedSince(new DateTime('2017-05-06T09:04:23+05:00'))
            ->withNumber(42)
            ->withSortField('createdAt')
            ->withSortOrder('asc')
            ->withQuery('query')
            ->withCustomFieldById(123, 'blue')
            ->withEmbed('threads');

        $this->assertSame([
            'mailbox' => 1,
            'folder' => 13,
            'status' => 'all',
            'assigned_to' => 1771,
            'number' => 42,
            'modifiedSince' => '2017-05-06T04:04:23Z',
            'sortField' => 'createdAt',
            'sortOrder' => 'asc',
            'query' => 'query',
            'embed' => 'threads',
            'tag' => 'testing',
            'customFieldsByIds' => '123:blue',
        ], $filters->getParams());
    }

    public function testMultipleCustomFields()
    {
        $filters = (new ConversationFilters())
            ->withCustomFieldsById([
                '123:blue',
                '456:yellow',
                '789:red',
                '11:none-more-black',
            ]);
        $this->assertSame([
            'customFieldsByIds' => '123:blue,456:yellow,789:red,11:none-more-black',
        ], $filters->getParams());
    }

    public function testMultipleTags()
    {
        $filters = (new ConversationFilters())
            ->withTags([
                'testing',
                'multiple',
                'tags',
            ]);

        $this->assertSame([
            'tag' => 'testing,multiple,tags',
        ], $filters->getParams());
    }

    public function testWithInvalidMailboxId()
    {
        $this->expectException(InvalidArgumentException::class);

        $filters = (new ConversationFilters())
            ->withMailbox(0);
    }

    /**
     * @dataProvider sortFieldProvider
     */
    public function testWithValidSortField($sortField)
    {
        $filters = (new ConversationFilters())
            ->withSortField($sortField);

        $this->assertSame([
            'sortField' => $sortField,
        ], $filters->getParams());
    }

    public function sortFieldProvider(): array
    {
        return [
            ['createdAt'],
            ['customerEmail'],
            ['mailboxid'],
            ['modifiedAt'],
            ['number'],
            ['score'],
            ['status'],
            ['subject'],
        ];
    }

    public function testWithInvalidSortField()
    {
        $this->expectException(InvalidArgumentException::class);

        $filters = (new ConversationFilters())
            ->withSortField('invalid');
    }

    /**
     * @dataProvider sortOrderProvider
     */
    public function testWithValidSortOrder($sortOrder, $sortOrderParam)
    {
        $filters = (new ConversationFilters())
            ->withSortOrder($sortOrder);

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

        $filters = (new ConversationFilters())
            ->withSortOrder('invalid');
    }

    public function testWithInvalidEmbed()
    {
        $this->expectException(InvalidArgumentException::class);

        $filters = (new ConversationFilters())
            ->withEmbed('invalid');
    }
}
