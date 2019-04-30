<?php

declare(strict_types=1);

namespace HelpScout\Api\Tests\Support;

use DateTime;
use DateTimeImmutable;
use DateTimeZone;
use HelpScout\Api\Exception\InvalidArgumentException;
use HelpScout\Api\Support\ExtractsData;
use PHPUnit\Framework\TestCase;

class ExtractsDataTest extends TestCase
{
    use ExtractsData;

    public function testConvertsTimestampToExpectedFormat()
    {
        $dateTime = new DateTime('1 hour ago');

        $this->assertEquals(0, $dateTime->getOffset());
        $this->assertEquals($dateTime->format('Y-m-d\TH:i:s\Z'), $this->to8601Utc($dateTime));
    }

    public function testConvertsTimestampToUTC()
    {
        $dateTime = new DateTime('1 hour ago');
        $dateTime->setTimezone(new DateTimeZone('Europe/Zurich'));

        $this->assertGreaterThan(0, $dateTime->getOffset());

        $expectedUTC = clone $dateTime;
        $expectedUTC->sub(date_interval_create_from_date_string('2 hours'));
        $this->assertEquals($expectedUTC->format('Y-m-d\TH:i:s\Z'), $this->to8601Utc($dateTime));
    }

    public function testRejectsTimestampsWithoutUTC()
    {
        $this->expectExceptionMessage('Timestamp must be UTC');
        $this->expectException(InvalidArgumentException::class);

        $dateTime = new DateTimeImmutable('1 hour ago', new DateTimeZone('Europe/Zurich'));

        $this->to8601Utc($dateTime);
    }
}
