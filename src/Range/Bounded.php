<?php

declare(strict_types=1);

namespace Shrikeh\DateTime\Range;

use DateTimeImmutable;
use DateTimeInterface;
use Generator;
use Shrikeh\DateTime\Period;
use Shrikeh\DateTime\Range\Exception\BoundedRangeMustHaveOneBoundary;
use Shrikeh\DateTime\Range\Exception\StartDateTimeCannotBeAfterEndDateTime;

final readonly class Bounded implements RangeInterface
{
    private Period $period;

    public function __construct(
        public RangeInterface $range,
        public ?DateTimeInterface $start = null,
        public ?DateTimeInterface $end = null,
    ) {
        $this->assertValid($start, $end);

        $start = $this->start ?? $this->range->earliest();
        $end = $this->end ?? $this->range->latest();
        $this->period = Period::create($start, $end);
    }

    public function __invoke(): Generator
    {
        $range = $this->range;
        foreach ($range() as $dateTime) {
            if ($this->period->covers($dateTime)) {
                yield $dateTime;
            }
        }
    }

    public function count(): int
    {
        return count(iterator_to_array($this()));
    }

    public function add(DateTimeInterface ...$dateTimes): RangeInterface
    {
        return new self(
            $this->range->add(...$dateTimes),
            $this->start,
            $this->end,
        );
    }

    public function earliest(): ?DateTimeImmutable
    {
        $bounded = $this();
        return $bounded->valid() ? $bounded->current() : null;
    }

    public function latest(): ?DateTimeImmutable
    {
        $bounded = null;
        foreach ($this() as $bounded) {
        }

        return $bounded;
    }

    public function period(): Period
    {
        return Period::fromRange($this);
    }

    private function assertValid(?DateTimeInterface $start = null, ?DateTimeInterface $end = null): void
    {
        if (!($start || $end)) {
            throw new BoundedRangeMustHaveOneBoundary();
        }
        if (($start && $end) && ($start >= $end)) {
            throw new StartDateTimeCannotBeAfterEndDateTime($start, $end);
        }
    }
}
