<?php

declare(strict_types=1);

namespace Shrikeh\DateTime\Range\Traits;

use DateTimeInterface;
use Generator;
use Iterator;
use Shrikeh\DateTime\Period;

trait InPeriod
{
    public function in(Period $period): Generator
    {
        foreach ($this->dates() as $date) {
            if ($period->covers($date)) {
                yield $date;
            }
        }
    }


    /**
     * @return Iterator<DateTimeInterface>
     */
    abstract private function dates(): Iterator;
}
