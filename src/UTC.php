<?php

declare(strict_types=1);

namespace Shrikeh\DateTime;

use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;

final class UTC
{
    private static DateTimeZone $utc;

    public static function convert(DateTimeInterface $date): DateTimeImmutable
    {
        return DateTimeImmutable::createFromInterface($date)->setTimezone(self::utc());
    }

    private static function utc(): DateTimeZone
    {
        if (!isset(self::$utc)) {
            self::$utc = new DateTimeZone('UTC');
        }

        return self::$utc;
    }
}
