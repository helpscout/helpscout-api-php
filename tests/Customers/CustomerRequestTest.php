<?php

declare(strict_types=1);

namespace HelpScout\Api\Tests\Customers;

use HelpScout\Api\Customers\CustomerLinks;
use HelpScout\Api\Customers\CustomerRequest;
use HelpScout\Api\Exception\InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class CustomerRequestTest extends TestCase
{
    /**
     * @dataProvider linkProvider
     */
    public function testAddsLinks($rel)
    {
        $links = [$rel];
        $request = new CustomerRequest($links);

        $this->assertSame($links, $request->getLinks());
        $this->assertTrue($request->hasLink($rel));
    }

    public function linkProvider()
    {
        return [
            [CustomerLinks::ADDRESS],
            [CustomerLinks::CHATS],
            [CustomerLinks::EMAILS],
            [CustomerLinks::PHONES],
            [CustomerLinks::SOCIAL_PROFILES],
            [CustomerLinks::WEBSITES],
        ];
    }

    public function testAssertsLinksAreValid()
    {
        $this->expectException(InvalidArgumentException::class);

        $request = new CustomerRequest(['invalid']);
    }

    public function testBuilder()
    {
        $request = (new CustomerRequest())
            ->withAddress()
            ->withChats()
            ->withEmails()
            ->withPhones()
            ->withSocialProfiles()
            ->withWebsites();

        $this->assertTrue($request->hasLink(CustomerLinks::ADDRESS));
        $this->assertTrue($request->hasLink(CustomerLinks::CHATS));
        $this->assertTrue($request->hasLink(CustomerLinks::EMAILS));
        $this->assertTrue($request->hasLink(CustomerLinks::PHONES));
        $this->assertTrue($request->hasLink(CustomerLinks::SOCIAL_PROFILES));
        $this->assertTrue($request->hasLink(CustomerLinks::WEBSITES));
    }
}
