<?php

declare(strict_types=1);

namespace Shrikeh\DateTime;

use DateTimeInterface;
use Shrikeh\DateTime\Range\Bounded;
use Shrikeh\DateTime\Range\RangeInterface;
use Shrikeh\DateTime\Range\Unbounded;

final readonly class Range
{
    public static function unbounded(DateTimeInterface ...$dateTimes): RangeInterface
    {
        return Unbounded::fromDateTimes(...$dateTimes);
    }

    public static function bounded(
        ?DateTimeInterface $start = null,
        ?DateTimeInterface $end = null,
        DateTimeInterface ...$dateTimes
    ): RangeInterface {
        $unbounded =  Unbounded::fromDateTimes(...$dateTimes);

        return Bounded::create($unbounded, $start, $end);
    }
}
