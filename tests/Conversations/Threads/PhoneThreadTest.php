<?php

declare(strict_types=1);

namespace HelpScout\Api\Tests\Conversations\Threads;

use HelpScout\Api\Conversations\Threads\PhoneThread;
use HelpScout\Api\Customers\Customer;
use HelpScout\Api\Support\HasCustomer;
use PHPUnit\Framework\TestCase;

class PhoneThreadTest extends TestCase
{
    public function testHasExpectedType()
    {
        $this->assertEquals('phone', PhoneThread::TYPE);
        $this->assertEquals('phone', (new PhoneThread())->getType());
    }

    public function testExtractsType()
    {
        $thread = new PhoneThread();
        $data = $thread->extract();
        $this->assertArrayHasKey('type', $data);
        $this->assertEquals(PhoneThread::TYPE, $data['type']);
    }

    public function testUsesExpectedTraits()
    {
        $classUses = class_uses(PhoneThread::class);

        $this->assertTrue(in_array(HasCustomer::class, $classUses));
    }

    public function testCustomerDefaultsToNull()
    {
        $thread = new PhoneThread();
        $this->assertNull($thread->getCustomer());
    }

    public function testCanSetCustomer()
    {
        $customer = new Customer();
        $customer->setId(4923);

        $thread = new PhoneThread();
        $this->assertInstanceOf(PhoneThread::class, $thread->setCustomer($customer));

        $this->assertEquals($customer, $thread->getCustomer());
    }

    public function testCanHydrateCustomer()
    {
        $thread = new PhoneThread();
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
        $thread = new PhoneThread();
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

        $thread = new PhoneThread();
        $this->assertInstanceOf(PhoneThread::class, $thread->setCustomer($customer));

        $data = $thread->extract();

        $this->assertArrayHasKey('customer', $data);
        $this->assertEquals(4923, $data['customer']['id']);
    }
}
