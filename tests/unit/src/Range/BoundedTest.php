<?php

declare(strict_types=1);

namespace Tests\Unit\Range;

use DateTime;
use DateTimeImmutable;
use Shrikeh\DateTime\Exception\Message;
use Shrikeh\DateTime\Range;
use Shrikeh\DateTime\Range\Bounded;
use PHPUnit\Framework\TestCase;
use Shrikeh\DateTime\Range\Exception\BoundedRangeMustHaveOneBoundary;
use Shrikeh\DateTime\Range\Exception\StartDateTimeCannotBeAfterEndDateTime;

final class BoundedTest extends TestCase
{

    public function testItCanAddDates(): void
    {
        $bounded = Range::bounded(
            new DateTimeImmutable('-2 hours'),
            new DateTimeImmutable(),
        );
        $bounded = $bounded->add(new DateTimeImmutable('-3 hours'));
        $this->assertNull($bounded->earliest());
    }

    public function testItIsCountable(): void
    {
        $sevenDays = new DateTime('+7 days');
        $now = new DateTimeImmutable();
        $threeHourAgo = new DateTimeImmutable('-3 hours');
        $twoHourAgo = new DateTimeImmutable('-2 hours');
        $anHourAgo = new DateTimeImmutable('-1 hours');

        $bounded = Range::bounded(
            new DateTimeImmutable('-2 hours'),
            new DateTimeImmutable(),
            $sevenDays,
            $threeHourAgo,
            $now,
            $anHourAgo,
            $twoHourAgo
        );

        $this->assertSame(2, $bounded->count());
    }
    public function testItCanBindTheStartDate(): void
    {
        $sevenDays = new DateTime('+7 days');
        $now = new DateTimeImmutable();
        $threeHourAgo = new DateTimeImmutable('-3 hours');
        $twoHourAgo = new DateTimeImmutable('-2 hours');
        $unbounded = Range::unbounded($sevenDays, $threeHourAgo, $now, $twoHourAgo);

        $bounded = Bounded::create(
            range: $unbounded,
            start: new DateTimeImmutable('-1 hour'),
        );
        $this->assertEquals($now, $bounded->earliest());
    }

    public function testItCanBoundTheEndDate(): void
    {
        $sevenDays = new DateTime('+7 days');
        $now = new DateTimeImmutable();
        $threeHourAgo = new DateTimeImmutable('-3 hours');
        $twoHourAgo = new DateTimeImmutable('-2 hours');

        $bounded = Range::bounded(
            null,
            new DateTimeImmutable('-1 hour'),
            $sevenDays,
            $threeHourAgo,
            $now,
            $twoHourAgo
        );
        $this->assertEquals($twoHourAgo, $bounded->latest());
    }

    public function testItReturnsAPeriod(): void
    {
        $sevenDays = new DateTime('+7 days');
        $now = new DateTimeImmutable();
        $threeHourAgo = new DateTimeImmutable('-3 hours');
        $twoHourAgo = new DateTimeImmutable('-2 hours');
        $anHourAgo = new DateTimeImmutable('-1 hours');

        $bounded = Range::bounded(
            new DateTimeImmutable('-2 hours'),
            new DateTimeImmutable(),
            $sevenDays,
            $threeHourAgo,
            $now,
            $anHourAgo,
            $twoHourAgo
        );
        $period = $bounded->toPeriod();
        $this->assertEquals($anHourAgo, $period->start);
        $this->assertEquals($now, $period->end);
    }

    public function testItThrowsAnExceptionIfThereIsNoStartDateAndEndBoundaries(): void
    {
        $sevenDays = new DateTime('+7 days');
        $now = new DateTimeImmutable();
        $twoHourAgo = new DateTimeImmutable('-2 hours');

        $unbounded = Range::unbounded($sevenDays, $now, $twoHourAgo);

        $this->expectExceptionObject(new BoundedRangeMustHaveOneBoundary());
        $this->expectExceptionMessage(Message::BOUNDED_RANGE_ONE_BOUNDARY->msg());

        Bounded::create($unbounded);
    }

    public function testItThrowsAnExceptionIfTheStartBoundaryIsAfterTheEndBoundary(): void
    {
        $end = new DateTimeImmutable();
        $start = new DateTimeImmutable('+1 second');

        $this->expectExceptionObject(new StartDateTimeCannotBeAfterEndDateTime($start, $end));
        $this->expectExceptionMessage(Message::START_BOUNDARY_AFTER_END_BOUNDARY->msg(
            $start->format('U.u'),
            $end->format('U.u'),
        ));

        Bounded::create(Range::unbounded($start, $end,), $start, $end);
    }

    public function testItThrowsAnExceptionIfTheStartBoundaryIsEqualToTheEndBoundary(): void
    {
        $same = new DateTimeImmutable();

        $this->expectExceptionObject(new StartDateTimeCannotBeAfterEndDateTime($same, $same));
        $this->expectExceptionMessage(Message::START_BOUNDARY_AFTER_END_BOUNDARY->msg(
            $same->format('U.u'),
            $same->format('U.u'),
        ));

        Bounded::create(Range::unbounded($same, $same,), $same, $same);
    }
}
