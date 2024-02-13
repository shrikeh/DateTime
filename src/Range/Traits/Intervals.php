<?php

declare(strict_types=1);

namespace Shrikeh\DateTime\Range\Traits;

use DateTimeInterface;
use Generator;
use Iterator;

trait Intervals
{
    public function intervals(): Generator
    {
        $previous = null;
        foreach ($this->dates() as $date) {
            if ($previous instanceof DateTimeInterface) {
                yield $previous => $previous->diff($date);
            }
            $previous = $date;
        }
    }

    /**
     * @return Iterator<DateTimeInterface>
     */
    abstract private function dates(): Iterator;
}
