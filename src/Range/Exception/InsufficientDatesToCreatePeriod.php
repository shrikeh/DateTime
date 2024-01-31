<?php

declare(strict_types=1);

namespace Shrikeh\DateTime\Range\Exception;

use RuntimeException;
use Shrikeh\DateTime\Exception\Message;
use Shrikeh\DateTime\Range;
use Shrikeh\DateTime\Range\RangeInterface;

final class InsufficientDatesToCreatePeriod extends RuntimeException implements UnboundedRangeException
{
    private const MSG = Message::INSUFFICIENT_DATES_FOR_PERIOD;

    /**
     * @param RangeInterface $range
     */
    public function __construct(public readonly RangeInterface $range)
    {
        parent::__construct(self::MSG->msg($this->range->count()));
    }
}
