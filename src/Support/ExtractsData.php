<?php

declare(strict_types=1);

namespace HelpScout\Api\Support;

use DateTime;
use HelpScout\Api\Exception\InvalidArgumentException;

trait ExtractsData
{
    /**
     * Convert DateTime to a particular version of the 8601 spec in UTC.
     *
     * @see https://developer.helpscout.com/mailbox-api/overview/time/
     */
    private function to8601Utc(\DateTimeInterface $dateTime): string
    {
        // Convert to UTC if we can
        if ($dateTime instanceof DateTime) {
            $dateTime->setTimezone(new \DateTimeZone('UTC'));
        } elseif ($dateTime instanceof \DateTimeImmutable && $dateTime->getOffset() > 0) {
            // Some implementations will be wrong since we're effectively dropping the offset from the datetime format
            // and this interface doesn't allow us to make modifications so we reject the timestamp altogether to ensure
            // only correct timestamps make it through.
            throw new InvalidArgumentException('Timestamp must be UTC');
        }

        // PHP's default 8601 format contains a portion that includes the timezone offset.  The Mailbox API doesn't
        // like the offset and requires a timestamp without it, thus requiring all inbound timestamps to be UTC
        return $dateTime->format('Y-m-d\TH:i:s\Z');
    }
}
