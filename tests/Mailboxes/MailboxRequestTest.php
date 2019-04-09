<?php

declare(strict_types=1);

namespace HelpScout\Api\Tests\Mailboxes;

use HelpScout\Api\Exception\InvalidArgumentException;
use HelpScout\Api\Mailboxes\MailboxLinks;
use HelpScout\Api\Mailboxes\MailboxRequest;
use PHPUnit\Framework\TestCase;

class MailboxRequestTest extends TestCase
{
    /**
     * @dataProvider linkProvider
     */
    public function testAddsLinks($rel)
    {
        $links = [$rel];
        $request = new MailboxRequest($links);

        $this->assertSame($links, $request->getLinks());
        $this->assertTrue($request->hasLink($rel));
    }

    public function linkProvider()
    {
        return [
            [MailboxLinks::FIELDS],
            [MailboxLinks::FOLDERS],
        ];
    }

    public function testAssertsLinksAreValid()
    {
        $this->expectException(InvalidArgumentException::class);

        $request = new MailboxRequest(['invalid']);
    }

    public function testBuilder()
    {
        $request = (new MailboxRequest())
            ->withFields()
            ->withFolders();

        $this->assertTrue($request->hasLink(MailboxLinks::FIELDS));
        $this->assertTrue($request->hasLink(MailboxLinks::FOLDERS));
    }
}
