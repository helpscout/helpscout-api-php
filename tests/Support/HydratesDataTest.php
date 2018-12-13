<?php

declare(strict_types=1);

namespace HelpScout\Api\Tests\Support;

use DateTime;
use HelpScout\Api\Support\HydratesData;
use PHPUnit\Framework\TestCase;

class HydratesDataTest extends TestCase
{
    use HydratesData;

    public function testTransformsDateTimeToNullWhenNull()
    {
        $this->assertNull($this->transformDateTime(null));
    }

    public function testTransformsDateTimeToDateTimeWhenNull()
    {
        $timestamp = new DateTime();
        $dateTime = $this->transformDateTime($timestamp->format('c'));

        $this->assertInstanceOf(DateTime::class, $dateTime);
    }
}
