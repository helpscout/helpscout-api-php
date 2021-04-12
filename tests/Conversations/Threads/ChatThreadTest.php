<?php

declare(strict_types=1);

namespace HelpScout\Api\Tests\Conversations\Threads;

use HelpScout\Api\Conversations\Threads\ChatThread;
use HelpScout\Api\Customers\Customer;
use HelpScout\Api\Support\HasCustomer;
use PHPUnit\Framework\TestCase;

class ChatThreadTest extends TestCase
{
    public function testHasExpectedType()
    {
        $this->assertEquals('chat', ChatThread::TYPE);
        $this->assertEquals('chat', (new ChatThread())->getType());
    }

    public function testExtractsType()
    {
        $thread = new ChatThread();
        $data = $thread->extract();
        $this->assertArrayHasKey('type', $data);
        $this->assertEquals(ChatThread::TYPE, $data['type']);
    }

    public function testHasExpectedResourceUrl()
    {
        $this->assertEquals('/v2/conversations/123/chats', ChatThread::resourceUrl(123));
    }

    public function testUsesExpectedTraits()
    {
        $classUses = class_uses(ChatThread::class);

        $this->assertTrue(in_array(HasCustomer::class, $classUses));
    }

    public function testCustomerDefaultsToNull()
    {
        $thread = new ChatThread();
        $this->assertNull($thread->getCustomer());
    }

    public function testCanSetCustomer()
    {
        $customer = new Customer();
        $customer->setId(4923);

        $thread = new ChatThread();
        $this->assertInstanceOf(ChatThread::class, $thread->setCustomer($customer));

        $this->assertEquals($customer, $thread->getCustomer());
    }

    public function testCanHydrateCustomer()
    {
        $thread = new ChatThread();
        $thread->hydrate([
            'customer' => [
                'id' => 132489,
            ],
        ]);

        $customer = $thread->getCustomer();
        $this->assertEquals(132489, $customer->getId());
    }

    public function testCanHydrateCustomerWithEmail()
    {
        $thread = new ChatThread();
        $thread->hydrate([
            'customer' => [
                'id' => 132489,
                'email' => 'customer@mydomain.com',
            ],
        ]);

        $customer = $thread->getCustomer();
        $this->assertEquals(132489, $customer->getId());
        $this->assertEquals('customer@mydomain.com', $customer->getEmails()->extract()[0]['value']);
    }

    public function testCanExtractCustomer()
    {
        $customer = new Customer();
        $customer->setId(4923);

        $thread = new ChatThread();
        $this->assertInstanceOf(ChatThread::class, $thread->setCustomer($customer));

        $data = $thread->extract();

        $this->assertArrayHasKey('customer', $data);
        $this->assertEquals(4923, $data['customer']['id']);
    }
}
