<?php

declare(strict_types=1);

namespace HelpScout\Api\Tests\Customers;

use HelpScout\Api\Customers\Customer;
use HelpScout\Api\Customers\Entry\ChatHandle;
use HelpScout\Api\Entity\Collection;
use PHPUnit\Framework\TestCase;

/**
 * These tests ensure we're still supporting deprecated methods.
 */
class CustomerDeprecatedTest extends TestCase
{
    public function testHydratesManyChats()
    {
        $customer = new Customer();
        $customer->hydrate([
            'createdAt' => '2017-04-21T14:39:56Z',
            'updatedAt' => '2017-04-21T14:43:24Z',
        ], [
            'chats' => [
                [
                    'id' => 123123,
                ],
                [
                    'id' => 456223,
                ],
            ],
        ]);

        $chats = $customer->getChats();

        // Only asserting one field here as the details of what's inflated are covered by the entity's tests
        $this->assertInstanceOf(ChatHandle::class, $chats[0]);
        $this->assertSame(123123, $chats[0]->getId());

        $this->assertInstanceOf(ChatHandle::class, $chats[1]);
        $this->assertSame(456223, $chats[1]->getId());
    }

    public function testSetChats()
    {
        $customer = new Customer();
        $chats = new Collection();
        $customer->setChats($chats);
        $this->assertEquals($chats, $customer->getChats());
    }

    public function testAddChat()
    {
        $customer = new Customer();
        $this->assertEmpty($customer->getChats());

        $chat = new ChatHandle();
        $customer->addChat($chat);
        $this->assertSame($chat, $customer->getChats()->toArray()[0]);
    }
}
