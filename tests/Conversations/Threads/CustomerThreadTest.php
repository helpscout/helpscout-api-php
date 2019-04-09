<?php

declare(strict_types=1);

namespace HelpScout\Api\Tests\Conversations\Threads;

use HelpScout\Api\Conversations\Threads\CustomerThread;
use HelpScout\Api\Conversations\Threads\Support\HasCustomer;
use HelpScout\Api\Conversations\Threads\Support\HasPartiesToBeNotified;
use HelpScout\Api\Customers\Customer;
use PHPUnit\Framework\TestCase;

class CustomerThreadTest extends TestCase
{
    public function testHasExpectedType()
    {
        $this->assertEquals('customer', CustomerThread::TYPE);
    }

    public function testExtractsType()
    {
        $thread = new CustomerThread();
        $data = $thread->extract();
        $this->assertArrayHasKey('type', $data);
        $this->assertEquals(CustomerThread::TYPE, $data['type']);
    }

    public function testHasExpectedResourceUrl()
    {
        $this->assertEquals('/v2/conversations/123/customer', CustomerThread::resourceUrl(123));
    }

    public function testUsesExpectedTraits()
    {
        $classUses = class_uses(CustomerThread::class);

        $this->assertTrue(in_array(HasCustomer::class, $classUses));
        $this->assertTrue(in_array(HasPartiesToBeNotified::class, $classUses));
    }

    public function testCustomerDefaultsToNull()
    {
        $thread = new CustomerThread();
        $this->assertNull($thread->getCustomer());
    }

    public function testCanSetCustomer()
    {
        $customer = new Customer();
        $customer->setId(4923);

        $thread = new CustomerThread();
        $thread->setCustomer($customer);

        $this->assertEquals($customer, $thread->getCustomer());
    }

    public function testCanHydrateCCAndBCCAsStrings()
    {
        $cc = 'tester@asdf234.com';
        $bcc = 'tester23@43jdf.com';

        $thread = new CustomerThread();
        $thread->hydrate([
            'cc' => $cc,
            'bcc' => $bcc,
        ]);

        $this->assertTrue(in_array($cc, $thread->getCC()));
        $this->assertTrue(in_array($bcc, $thread->getBCC()));
    }

    public function testCanHydrateCustomer()
    {
        $thread = new CustomerThread();
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
        $thread = new CustomerThread();
        $thread->hydrate([
            'customer' => [
                'id' => 132489,
                'email' => 'customer@mydomain.com',
            ],
        ]);

        $customer = $thread->getCustomer();
        $this->assertEquals(132489, $customer->getId());
        $this->assertEquals('customer@mydomain.com', $customer->getEmails()->toArray()[0]);
    }

    public function testCanExtractCustomer()
    {
        $customer = new Customer();
        $customer->setId(4923);

        $thread = new CustomerThread();
        $thread->setCustomer($customer);

        $data = $thread->extract();

        $this->assertArrayHasKey('customer', $data);
        $this->assertEquals(4923, $data['customer']['id']);
    }
}
