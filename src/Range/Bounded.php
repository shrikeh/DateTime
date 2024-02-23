<?php

declare(strict_types=1);

namespace Shrikeh\DateTime\Range;

use DateTimeInterface;
use Generator;
use Shrikeh\DateTime\Period;
use Shrikeh\DateTime\Range\Exception\BoundedRangeMustHaveOneBoundary;
use Shrikeh\DateTime\Range\Exception\StartDateTimeCannotBeAfterEndDateTime;
use Shrikeh\DateTime\Range\Traits\Count;
use Shrikeh\DateTime\Range\Traits\Earliest;
use Shrikeh\DateTime\Range\Traits\InPeriod;
use Shrikeh\DateTime\Range\Traits\Intervals;
use Shrikeh\DateTime\Range\Traits\Invokeable;
use Shrikeh\DateTime\Range\Traits\Latest;
use Shrikeh\DateTime\Range\Traits\ToPeriod;

final readonly class Bounded implements RangeInterface
{
    use InPeriod;
    use Count;
    use Invokeable;
    use Intervals;
    use ToPeriod;
    use Earliest;
    use Latest;

    public static function create(
        RangeInterface $range,
        ?DateTimeInterface $start = null,
        ?DateTimeInterface $end = null,
    ): self {
        if (!($start || $end)) {
            throw new BoundedRangeMustHaveOneBoundary();
        }
        if (($start && $end) && ($start >= $end)) {
            throw new StartDateTimeCannotBeAfterEndDateTime($start, $end);
        }

        $start = $start ?? $range->earliest();
        $end = $end ?? $range->latest();

        return new self($range, Period::create($start, $end));
    }

    public function __construct(
        public RangeInterface $range,
        public Period $period
    ) {
    }

    public function add(DateTimeInterface ...$dateTimes): RangeInterface
    {
        return new self(
            $this->range->add(...$dateTimes),
            $this->period,
        );
    }

    private function dates(): Generator
    {
        $range = $this->range;
        foreach ($range() as $dateTime) {
            if ($this->period->covers($dateTime)) {
                yield $dateTime;
            }
        }
    }
}
