<?php

declare(strict_types=1);

namespace Shrikeh\DateTime\Range;

use Countable;
use DateTimeImmutable;
use DateTimeInterface;
use Shrikeh\DateTime\Period;

interface RangeInterface extends Countable
{
    public function __invoke(): iterable;

    public function add(DateTimeInterface ...$dateTimes): self;

    /**
     * @return DateTimeImmutable|null
     */
    public function earliest(): ?DateTimeImmutable;

    /**
     * @return DateTimeImmutable|null
     */
    public function latest(): ?DateTimeImmutable;

    public function period(): Period;
}
