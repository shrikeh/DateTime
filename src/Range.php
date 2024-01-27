<?php

declare(strict_types=1);

namespace Shrikeh\DateTime;

use Countable;
use DateTimeImmutable;
use DateTimeInterface;
use Generator;
use Shrikeh\DateTime\Range\Exception\InsufficientDatesToCreatePeriod;
use SplFixedArray;

final readonly class Range implements Countable
{
    /**
     * @var SplFixedArray<DateTimeImmutable>
     */
    public SplFixedArray $dateTimes;

    /**
     * @param DateTimeInterface ...$dateTimes
     * @return self
     */
    public static function fromDateTimes(DateTimeInterface ...$dateTimes): self
    {
        return new self(...array_map(static function (DateTimeInterface $dateTime): DateTimeImmutable {
            return DateTimeImmutable::createFromInterface($dateTime);
        }, $dateTimes));
    }

    /**
     * @param DateTimeImmutable ...$dateTimes
     */
    private function __construct(DateTimeImmutable ...$dateTimes)
    {
        usort($dateTimes, static function (DateTimeImmutable $first, DateTimeImmutable $second): int {
            return $first <=> $second;
        });

        $this->dateTimes = SplFixedArray::fromArray($dateTimes);
    }

    /**
     * @return Generator<DateTimeImmutable>
     */
    public function __invoke(): Generator
    {
        foreach ($this->dateTimes as $dateTime) {
            yield $dateTime;
        }
    }

    /**
     * @return Period
     */
    public function period(): Period
    {
        if ($this->count() < 2) {
            throw new InsufficientDatesToCreatePeriod($this);
        }
        return Period::fromRange($this);
    }

    /**
     * @param DateTimeInterface ...$dateTimes
     * @return self
     */
    public function add(DateTimeInterface ...$dateTimes): self
    {
        return self::fromDateTimes(...array_merge((array) $this->dateTimes, $dateTimes));
    }

    /**
     * @return DateTimeImmutable
     */
    public function earliest(): DateTimeImmutable
    {
        return $this->dateTimes[array_key_first($this->dateTimes->toArray())];
    }

    /**
     * @return DateTimeImmutable
     */
    public function latest(): DateTimeImmutable
    {
        return $this->dateTimes[array_key_last($this->dateTimes->toArray())];
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return $this->dateTimes->count();
    }
}
