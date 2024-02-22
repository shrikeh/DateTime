<?php

declare(strict_types=1);

namespace Shrikeh\DateTime\Range\Traits;

use DateTimeInterface;
use Iterator;
use Shrikeh\DateTime\Period;
use Shrikeh\DateTime\Range\Exception\InsufficientDatesToCreatePeriod;

trait ToPeriod
{
    /**
     * @return Period
     */
    public function toPeriod(): Period
    {
        if ($this->count() < 2) {
            throw new InsufficientDatesToCreatePeriod($this->count());
        }
        return Period::create(...$this->dates());
    }

    /**
     * @return Iterator<DateTimeInterface>
     */
    abstract private function dates(): Iterator;

    /**
     * @return int
     */
    abstract public function count(): int;
}
