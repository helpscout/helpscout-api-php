<?php

declare(strict_types=1);

namespace HelpScout\Api\Tests\Conversations\Threads;

use HelpScout\Api\Conversations\Threads\ReplyThread;
use HelpScout\Api\Customers\Customer;
use PHPUnit\Framework\TestCase;

class ReplyThreadTest extends TestCase
{
    public function testHasExpectedType()
    {
        $this->assertEquals('reply', ReplyThread::TYPE);
    }

    public function testExtractsType()
    {
        $thread = new ReplyThread();
        $data = $thread->extract();
        $this->assertArrayHasKey('type', $data);
        $this->assertEquals(ReplyThread::TYPE, $data['type']);
    }

    public function testHasExpectedResourceUrl()
    {
        $this->assertEquals('/v2/conversations/123/reply', ReplyThread::resourceUrl(123));
    }

    public function testDefaultsAsPublished()
    {
        $thread = new ReplyThread();

        $this->assertFalse($thread->isDraft());
    }

    public function testCanBeDraft()
    {
        $thread = new ReplyThread();
        $thread->asDraft();

        $this->assertTrue($thread->isDraft());
    }

    public function testCanBePublishedAfterHavingBeenDraft()
    {
        $thread = new ReplyThread();
        $thread->asDraft();
        $thread->notAsDraft();

        $this->assertFalse($thread->isDraft());
    }

    public function testCanHydrateCCAndBCCAsStrings()
    {
        $cc = 'tester@asdf234.com';
        $bcc = 'tester23@43jdf.com';

        $thread = new ReplyThread();
        $thread->hydrate([
            'cc' => $cc,
            'bcc' => $bcc,
        ]);

        $this->assertTrue(in_array($cc, $thread->getCC()));
        $this->assertTrue(in_array($bcc, $thread->getBCC()));
    }

    public function testCanHydrateCustomer()
    {
        $thread = new ReplyThread();
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
        $thread = new ReplyThread();
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

        $thread = new ReplyThread();
        $thread->setCustomer($customer);

        $data = $thread->extract();

        $this->assertArrayHasKey('customer', $data);
        $this->assertEquals(4923, $data['customer']['id']);
    }

    public function testCanExtractUser()
    {
        $thread = new ReplyThread();
        $thread->setUserId(94320);

        $data = $thread->extract();

        $this->assertArrayHasKey('user', $data);
        $this->assertEquals(94320, $data['user']);
    }

    public function testExtractsDraft()
    {
        $thread = new ReplyThread();
        $data = $thread->extract();
        $this->assertFalse($data['draft']);

        $thread->asDraft();
        $data = $thread->extract();
        $this->assertTrue($data['draft']);
    }
}
