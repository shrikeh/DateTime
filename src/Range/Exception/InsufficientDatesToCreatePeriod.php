<?php

declare(strict_types=1);

namespace Shrikeh\DateTime\Range\Exception;

use RuntimeException;
use Shrikeh\DateTime\Range;

final class InsufficientDatesToCreatePeriod extends RuntimeException implements RangeException
{
    public function __construct(public readonly Range $range)
    {
        parent::__construct(
            sprintf(
                'Two or more datetimes are required to build a Period from a Range, but there are only %d',
                $this->range->count(),
            )
        );
    }
}