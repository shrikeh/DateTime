<?php

declare(strict_types=1);

namespace Shrikeh\DateTime\Period;

use DateInterval;

final class DurationDateInterval extends DateInterval
{
    public const FORMAT = 'P%04d-%02d-%02dT%02d:%02d:%02d.';

    public static function normalize(DateInterval $dateInterval): static
    {
        return $dateInterval instanceof static ? $dateInterval : static::fromDateInterval($dateInterval);
    }
    public static function fromDateInterval(DateInterval $dateInterval): self
    {
        return new static(static::toDuration($dateInterval));
    }

    public function __toString(): string
    {
        return $this->toString();
    }

    public function toString(): string
    {
        return self::toDuration($this);
    }

    private static function toDuration(DateInterval $dateInterval): string
    {
        return sprintf(
            self::FORMAT,
            $dateInterval->y,
            $dateInterval->m,
            $dateInterval->d,
            $dateInterval->h,
            $dateInterval->i,
            $dateInterval->s,
        );
    }
}
