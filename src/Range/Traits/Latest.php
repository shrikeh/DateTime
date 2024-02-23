<?php

declare(strict_types=1);

namespace Shrikeh\DateTime\Range\Traits;

use DateTimeInterface;
use Iterator;

trait Latest
{
    public function latest(): ?DateTimeInterface
    {
        $sorted = iterator_to_array($this->dates());

        $latest = array_key_last($sorted);

        return (null !== $latest) ? $sorted[$latest] : null;
    }

    /**
     * @return Iterator<DateTimeInterface>
     */
    abstract private function dates(): Iterator;
}
