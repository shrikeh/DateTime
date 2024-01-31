<?php

declare(strict_types=1);

namespace Shrikeh\DateTime;

use DateInterval;
use DatePeriod;
use DateTimeImmutable;
use DateTimeInterface;
use Generator;
use Shrikeh\DateTime\Range\RangeInterface;
use Shrikeh\DateTime\Range\Unbounded;

readonly final class Period
{
    /**
     * @param DateTimeInterface $first
     * @param DateTimeInterface $second
     * @return self
     */
    public static function create(DateTimeInterface $first, DateTimeInterface $second): self
    {
        return self::fromRange(Unbounded::fromDateTimes($first, $second));
    }

    /**
     * @param RangeInterface $range
     * @return self
     */
    public static function fromRange(RangeInterface $range): self
    {
        return new self($range->earliest(), $range->latest());
    }

    /**
     * @param DateTimeImmutable $start
     * @param DateTimeImmutable $end
     */
    private function __construct(public DateTimeImmutable $start, public DateTimeImmutable $end)
    {
    }

    /**
     * @param Period $period
     * @return bool
     */
    public function intersects(Period $period): bool
    {
        return max($this->start, $period->start) < min($this->end, $period->end);
    }

    /**
     * @param DateTimeImmutable $dateTime
     * @return bool
     */
    public function covers(DateTimeImmutable $dateTime): bool
    {
        return ($dateTime > $this->start) && ($dateTime < $this->end);
    }

    /**
     * @return DateInterval
     */
    public function interval(): DateInterval
    {
        return $this->start->diff($this->end);
    }

    public function move(DateTimeInterface|DateInterval $start): self
    {
        $diff = $this->interval();

        if (($start instanceof DateInterval)) {
            $start = $this->start->add($start);
        } else {
            $start = DateTimeImmutable::createFromInterface($start);
        }

        $end = $start->add($diff);

        return new self($start, $end);
    }

    public function recurUntil(DateInterval $offset, DateTimeInterface $until, bool $includeEndDate = true): Generator
    {
        $datePeriod = new DatePeriod($this->end, $offset, $until, $this->recurrenceOptions($includeEndDate));
        foreach ($datePeriod as $end) {
            yield new self($end->sub($this->interval()), $end);
        }
    }

    private function recurrenceOptions(bool $includeEndDate): int
    {
        if ($includeEndDate) {
            return DatePeriod::EXCLUDE_START_DATE | DatePeriod::INCLUDE_END_DATE;
        } else {
            return DatePeriod::EXCLUDE_START_DATE;
        }
    }
}
