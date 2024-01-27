<?php

declare(strict_types=1);

use Shrikeh\DateTime\Range;

require_once dirname(__DIR__) . '/vendor/autoload.php';

$range = Range::fromDateTimes(
    new DateTime('+7 days'),
    new DateTimeImmutable(),
    new DateTime('+5 hours'),
    new DateTimeImmutable('-1 hour'),
);

/**
 * These will now be sorted from earliest to latest
 */
foreach ($range() as $dateTime) {
    print sprintf("%s\n", $dateTime->format(DATE_ATOM));
}

/**
 * Convert the range into a period spanning the earliest and latest datetimes
 */
$period = $range->period();
print "Showing period for the range:\n";
print sprintf("%s\n", $period->start->format(DATE_ATOM));
print sprintf("%s\n", $period->end->format(DATE_ATOM));

/**
 * See if a date is "covered" by a Period
 */
var_dump($period->covers(new DateTimeImmutable()));
var_dump($period->covers(new DateTimeImmutable('-1 year')));
