<?php

declare(strict_types=1);

namespace Shrikeh\DateTime\Range;

use ArrayObject;
use DateTimeInterface;
use Generator;
use Shrikeh\DateTime\Range\Traits\Count;
use Shrikeh\DateTime\Range\Traits\Earliest;
use Shrikeh\DateTime\Range\Traits\InPeriod;
use Shrikeh\DateTime\Range\Traits\Intervals;
use Shrikeh\DateTime\Range\Traits\Invokeable;
use Shrikeh\DateTime\Range\Traits\Latest;
use Shrikeh\DateTime\Range\Traits\ToPeriod;
use SplFixedArray;

final readonly class Unbounded implements RangeInterface
{
    use InPeriod;
    use Count;
    use Earliest;
    use Latest;
    use Invokeable;
    use Intervals;
    use ToPeriod;

    /**
     * @param DateTimeInterface ...$dateTimes
     * @return self
     */
    public static function fromDateTimes(DateTimeInterface ...$dateTimes): self
    {
        return new self(SplFixedArray::fromArray($dateTimes));
    }

    /**
     * @param SplFixedArray<DateTimeInterface> $dateTimes
     */
    private function __construct(private SplFixedArray $dateTimes)
    {
    }

    /**
     * @inheritDoc
     */
    public function add(DateTimeInterface ...$dateTimes): self
    {
        return self::fromDateTimes(...array_merge(iterator_to_array($this->dates()), $dateTimes));
    }

    /**
     * @return Generator<DateTimeInterface>
     */
    private function dates(): Generator
    {
        $dates = new ArrayObject($this->dateTimes->toArray());

        $dates->uasort(static function (DateTimeInterface $first, DateTimeInterface $second): int {
            return $first <=> $second;
        });

        yield from $dates;
    }
}
