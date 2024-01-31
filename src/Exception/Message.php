<?php

declare(strict_types=1);

namespace Shrikeh\DateTime\Exception;

enum Message: string
{
    case INSUFFICIENT_DATES_FOR_PERIOD =
        'Two or more datetimes are required to build a Period from a Unbounded, but there are only %d';

    case BOUNDED_RANGE_ONE_BOUNDARY = 'Bounded Range must include a start or an end boundary';

    case START_BOUNDARY_AFTER_END_BOUNDARY = 'The start datetime (%s) cannot be after the end date (%s)';
    public function msg(mixed ...$args): string
    {
        return vsprintf($this->value, $args);
    }
}
