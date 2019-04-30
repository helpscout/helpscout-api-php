<?php

declare(strict_types=1);

namespace HelpScout\Api\Tests\Conversations;

use DateTime;
use HelpScout\Api\Conversations\CustomerWaitingSince;
use PHPUnit\Framework\TestCase;

class CustomerWaitingSinceTest extends TestCase
{
    public function testHydrate()
    {
        $waitingSince = new CustomerWaitingSince();
        $waitingSince->hydrate([
            'time' => '2017-07-24T20:18:33Z',
            'friendly' => '20 hours ago',
            'latestReplyFrom' => 'customer',
        ]);

        $this->assertSame('2017-07-24T20:18:33+00:00', $waitingSince->getTime()->format('c'));
        $this->assertSame('20 hours ago', $waitingSince->getFriendly());
        $this->assertSame('customer', $waitingSince->getLatestReplyFrom());
    }

    public function testExtract()
    {
        $waitingSince = new CustomerWaitingSince();
        $waitingSince->setTime(new DateTime('2017-07-24T20:18:33Z'));
        $waitingSince->setFriendly('20 hours ago');
        $waitingSince->setLatestReplyFrom('customer');

        $this->assertSame([
            'time' => '2017-07-24T20:18:33Z',
            'friendly' => '20 hours ago',
            'latestReplyFrom' => 'customer',
        ], $waitingSince->extract());
    }

    public function testExtractNewEntity()
    {
        $waitingSince = new CustomerWaitingSince();

        $this->assertSame([
            'time' => null,
            'friendly' => null,
            'latestReplyFrom' => null,
        ], $waitingSince->extract());
    }
}
