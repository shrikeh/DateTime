<?php

declare(strict_types=1);

use Shrikeh\DateTime\Range;

require_once dirname(__DIR__, 2) . '/vendor/autoload.php';

$dateTimes = static function(): Generator {
    for ($i=0; $i<100; $i++) {
        yield new DateTimeImmutable(sprintf('+%d seconds', rand(1, 10000)));
    }
};

$bounded = Range::bounded(
    new DateTimeImmutable('+10 seconds'),
    new DateTimeImmutable('+1000 seconds'),
    ... $dateTimes(),
);


/**
 * These will now be sorted from earliest to latest
 */
foreach ($bounded() as $dateTime) {
    print sprintf("%s\n", $dateTime->format(DATE_ATOM));
}

/**
 * Convert the range into a period spanning the earliest and latest datetimes
 */
$period = $bounded->period();
print "Showing period for the unbounded range:\n";
print sprintf("%s\n", $period->start->format(DATE_ATOM));
print sprintf("%s\n", $period->end->format(DATE_ATOM));

/**
 * See if a date is "covered" by a Period. Thos can sometimes return false for both as the numbers are random!
 */
var_dump($period->covers(new DateTimeImmutable('+300 seconds')));
var_dump($period->covers(new DateTimeImmutable('-1 year')));
