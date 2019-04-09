<?php

declare(strict_types=1);

namespace HelpScout\Api\Tests\Conversations;

use HelpScout\Api\Conversations\ConversationLinks;
use HelpScout\Api\Conversations\ConversationRequest;
use HelpScout\Api\Exception\InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class ConversationRequestTest extends TestCase
{
    /**
     * @dataProvider linkProvider
     */
    public function testAddsLinks($rel)
    {
        $links = [$rel];
        $request = new ConversationRequest($links);

        $this->assertSame($links, $request->getLinks());
        $this->assertTrue($request->hasLink($rel));
    }

    public function linkProvider()
    {
        return [
            [ConversationLinks::MAILBOX],
            [ConversationLinks::PRIMARY_CUSTOMER],
            [ConversationLinks::CREATED_BY_CUSTOMER],
            [ConversationLinks::CREATED_BY_USER],
            [ConversationLinks::CLOSED_BY],
            [ConversationLinks::THREADS],
            [ConversationLinks::ASSIGNEE],
            [ConversationLinks::WEB],
        ];
    }

    public function testAssertsLinksAreValid()
    {
        $this->expectException(InvalidArgumentException::class);

        $request = new ConversationRequest(['invalid']);
    }

    public function testBuilder()
    {
        $request = (new ConversationRequest())
            ->withMailbox()
            ->withPrimaryCustomer()
            ->withCreatedByCustomer()
            ->withCreatedByUser()
            ->withClosedBy()
            ->withThreads()
            ->withAssignee()
            ->withWeb();

        $this->assertTrue($request->hasLink(ConversationLinks::MAILBOX));
        $this->assertTrue($request->hasLink(ConversationLinks::PRIMARY_CUSTOMER));
        $this->assertTrue($request->hasLink(ConversationLinks::CREATED_BY_CUSTOMER));
        $this->assertTrue($request->hasLink(ConversationLinks::CREATED_BY_USER));
        $this->assertTrue($request->hasLink(ConversationLinks::CLOSED_BY));
        $this->assertTrue($request->hasLink(ConversationLinks::THREADS));
        $this->assertTrue($request->hasLink(ConversationLinks::ASSIGNEE));
        $this->assertTrue($request->hasLink(ConversationLinks::WEB));
    }

    public function testBuilderDefaultsToLazy()
    {
        $request = new ConversationRequest();

        $this->assertFalse($request->hasLink(ConversationLinks::MAILBOX));
        $this->assertFalse($request->hasLink(ConversationLinks::PRIMARY_CUSTOMER));
        $this->assertFalse($request->hasLink(ConversationLinks::CREATED_BY_CUSTOMER));
        $this->assertFalse($request->hasLink(ConversationLinks::CREATED_BY_USER));
        $this->assertFalse($request->hasLink(ConversationLinks::CLOSED_BY));
        $this->assertFalse($request->hasLink(ConversationLinks::THREADS));
        $this->assertFalse($request->hasLink(ConversationLinks::ASSIGNEE));
        $this->assertFalse($request->hasLink(ConversationLinks::WEB));
    }
}
