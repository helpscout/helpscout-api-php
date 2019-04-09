<?php

declare(strict_types=1);

namespace HelpScout\Api\Tests\Customers\Entry;

use HelpScout\Api\Conversations\Threads\PhoneThread;
use HelpScout\Api\Customers\Entry\Phone;
use PHPUnit\Framework\TestCase;

class PhoneTest extends TestCase
{
    public function testHasExpectedType()
    {
        $this->assertEquals('phone', PhoneThread::TYPE);
    }

    public function testHasExpectedResourceUrl()
    {
        $this->assertEquals('/v2/conversations/123/phones', PhoneThread::resourceUrl(123));
    }

    public function testHydrate()
    {
        $phone = new Phone();
        $phone->hydrate([
            'id' => 12,
            'value' => '123456789',
            'type' => 'work',
        ]);

        $this->assertSame(12, $phone->getId());
        $this->assertSame('123456789', $phone->getValue());
        $this->assertSame('work', $phone->getType());
    }

    public function testExtract()
    {
        $phone = new Phone();
        $phone->setId(12);
        $phone->setValue('123456789');
        $phone->setType('work');

        $this->assertSame([
            'value' => '123456789',
            'type' => 'work',
        ], $phone->extract());
    }

    public function testExtractNewEntity()
    {
        $phone = new Phone();

        $this->assertSame([
            'value' => null,
            'type' => null,
        ], $phone->extract());
    }
}
