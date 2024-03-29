<?php

declare(strict_types=1);

namespace Shrikeh\DateTime;

use DateInterval;
use DatePeriod;
use DateTimeImmutable;
use DateTimeInterface;
use Generator;
use Shrikeh\DateTime\Period\DurationDateInterval;
use Shrikeh\DateTime\Range\RangeInterface;
use Shrikeh\DateTime\Range\Unbounded;

readonly final class Period
{
    /**
     * @param DateTimeInterface ...$dates
     * @return self
     */
    public static function create(DateTimeInterface ...$dates): self
    {
        return self::fromRange(Unbounded::fromDateTimes(...$dates));
    }

    /**
     * @param RangeInterface $range
     * @return self
     */
    public static function fromRange(RangeInterface $range): self
    {
        return new self(
            DateTimeImmutable::createFromInterface($range->earliest()),
            DateTimeImmutable::createFromInterface($range->latest()),
        );
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
     * @param DateTimeInterface $dateTime
     * @return bool
     */
    public function covers(DateTimeInterface $dateTime): bool
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

    public function recurTimes(DateInterval $offset, int $recurTimes): Generator
    {
        $offset = DurationDateInterval::normalize($offset);
        $recurrences = DatePeriod::createFromISO8601String(
            sprintf(
                'R%d/%s/%s',
                $recurTimes,
                UTC::convert($this->end)->format('Y-m-d\TH:i:s\Z'),
                $offset->toString(),
            ),
            DatePeriod::EXCLUDE_START_DATE,
        );

        yield from $this->recur($recurrences);
    }

    public function recurUntil(DateInterval $offset, DateTimeInterface $until, bool $includeEndDate = true): Generator
    {
        $recurrences = new DatePeriod(
            $this->end,
            $offset,
            DateTimeImmutable::createFromInterface($until),
            $this->recurrenceOptions($includeEndDate)
        );

        yield from $this->recur($recurrences);
    }

    private function recur(DatePeriod $recurrences): Generator
    {
        /** @var DateTimeImmutable $end */
        foreach ($recurrences as $end) {
            $end = $end->setTimezone($this->end->getTimezone());
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
