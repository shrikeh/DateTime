<?php

declare(strict_types=1);

namespace Shrikeh\DateTime\Exception;

enum Message: string
{
    case INSUFFICIENT_DATES_FOR_PERIOD =
        'Two or more datetimes are required to build a Period from a Range, but there are only %d';

    public function msg(mixed ...$args): string
    {
        return vsprintf($this->value, $args);
    }
}
