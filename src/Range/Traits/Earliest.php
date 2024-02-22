<?php

declare(strict_types=1);

namespace Shrikeh\DateTime\Range\Traits;

use DateTimeInterface;
use Iterator;

trait Earliest
{
    public function earliest(): ?DateTimeInterface
    {
        $sorted = iterator_to_array($this->dates());

        $earliest = array_key_first($sorted);

        return (null !== $earliest) ? $sorted[$earliest]: null;
    }

    /**
     * @return Iterator<DateTimeInterface>
     */
    abstract private function dates(): Iterator;
}
