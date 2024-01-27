<?php

declare(strict_types=1);

namespace Shrikeh\DateTime\Range\Exception;

use RuntimeException;
use Shrikeh\DateTime\Exception\Message;
use Shrikeh\DateTime\Range;

final class InsufficientDatesToCreatePeriod extends RuntimeException implements RangeException
{
    private const MSG = Message::INSUFFICIENT_DATES_FOR_PERIOD;

    /**
     * @param Range $range
     */
    public function __construct(public readonly Range $range)
    {
        parent::__construct(self::MSG->msg($this->range->count()));
    }
}
