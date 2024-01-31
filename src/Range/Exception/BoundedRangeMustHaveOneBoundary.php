<?php

declare(strict_types=1);

namespace Shrikeh\DateTime\Range\Exception;

use InvalidArgumentException;
use Shrikeh\DateTime\Exception\Message;

final class BoundedRangeMustHaveOneBoundary extends InvalidArgumentException implements RangeException
{
    private const MSG = Message::BOUNDED_RANGE_ONE_BOUNDARY;
    public function __construct()
    {
        parent::__construct(self::MSG->msg());
    }
}
