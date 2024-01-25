<?php

declare(strict_types=1);

namespace Shrikeh\DateTime;

use DateInterval;
use DateTimeImmutable;
use DateTimeInterface;

readonly final class Period
{
    public static function create(DateTimeInterface $first, DateTimeInterface $second): self
    {
        return self::fromRange(Range::fromDateTimes($first, $second));
    }

    public static function fromRange(Range $range): self
    {
        return new self($range->earliest(), $range->latest());
    }

    private function __construct(public DateTimeImmutable $start, public DateTimeImmutable $end)
    {
    }

    public function intersects(Period $period): bool
    {
        return max($this->start, $period->start) < min($this->end, $period->end);
    }

    public function covers(DateTimeImmutable $dateTime): bool
    {
        return ($dateTime > $this->start) && ($dateTime < $this->end);
    }

    public function interval(): DateInterval
    {
        return $this->start->diff($this->end);
    }
}