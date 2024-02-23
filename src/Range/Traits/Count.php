<?php

declare(strict_types=1);

namespace Shrikeh\DateTime\Range\Traits;

use DateTimeInterface;
use Iterator;

trait Count
{
    /**
     * @return int
     */
    public function count(): int
    {
        return count(iterator_to_array($this->dates()));
    }

    /**
     * @return Iterator<DateTimeInterface>
     */
    abstract private function dates(): Iterator;
}
