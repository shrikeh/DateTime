# DateTime
Boilerplate collections representing DateTime periods and ranges.

## Overview

This adds two utility classes for DateTime usage: `Period`, a class to describe a DateTime "window" (such as a calendar meeting), and `Range`, which describes multiple dates and automatically sorts them, earliest to latest.

##  Installation

Installation is via composer:
```bash
composer require shrikeh/datetime
```

## Usage
Both classes are written to ensure dates are immutable and so use DateTimeImmutable internally.

### Range
Represent a range of dates. Given the following dates:
```php
<?php

$date1 = new DateTime('+7 days');
$date2 = new DateTimeImmutable();
$date3 = new DateTime('+5 hours');
$date4 = new DateTimeImmutable('-1 hour');
```
These can then be sorted simply by:
```php
$range = Range::fromDateTimes(
    $date1,
    $date2,
    $date3,
    $date4,
);

foreach ($range() as $date) {
// Will iterate as $date4, $date2, $date3, $date 1
}
```
The Range has two helper methods, `earliest()` and `latest()` which return the outer bounds of the DateTimes.
To create a Period from the Range is straightforward:

```php
$range = Range::fromDateTimes(
    new DateTimeImmutable(),
    new DateTimeImmutable('+2 years'),
    new DateTimeImmutable('+1 month'),
);

$period = $range->period();
```
In the above example, the Period will begin from now and end in two year's time.
