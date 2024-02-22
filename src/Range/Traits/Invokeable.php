<?php

declare(strict_types=1);

namespace Shrikeh\DateTime\Range\Traits;

use Iterator;
use DateTimeInterface;
use Generator;

trait Invokeable
{
    /**
     * @return Generator<DateTimeInterface>
     */
    public function __invoke(): Generator
    {
        foreach ($this->dates() as $dateTime) {
            yield $dateTime;
        }
    }
    /**
     * @return Iterator<DateTimeInterface>
     */
    abstract private function dates(): Iterator;
}
