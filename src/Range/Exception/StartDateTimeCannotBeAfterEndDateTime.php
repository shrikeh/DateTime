<?php

declare(strict_types=1);

namespace Shrikeh\DateTime\Range\Exception;

use DateTimeInterface;
use InvalidArgumentException;
use Shrikeh\DateTime\Exception\Message;

final class StartDateTimeCannotBeAfterEndDateTime extends InvalidArgumentException implements BoundedRangeException
{
    private const MSG = Message::START_BOUNDARY_AFTER_END_BOUNDARY;

    public function __construct(public readonly DateTimeInterface $start, public readonly DateTimeInterface $end)
    {
        parent::__construct(self::MSG->msg(
            $this->start->format('U.u'),
            $this->end->format('U.u'),
        ));
    }
}
