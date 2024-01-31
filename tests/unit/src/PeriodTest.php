<?php

declare(strict_types=1);

namespace Tests\Unit;

use DateInterval;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Shrikeh\DateTime\Period;

final class PeriodTest extends TestCase
{
    public function testItReturnsTrueIfItIntersectsAnotherPeriod(): void
    {
        $december = Period::create(
            new DateTimeImmutable('2024-12-01 00:00:00'),
            new DateTimeImmutable('2024-12-31 23:59:59'),
        );

        $christMasBreak = Period::create(
            new DateTimeImmutable('2024-12-24 00:00:00'),
            new DateTimeImmutable('2024-12-27 00:00:00'),
        );

        $newYearsEveAndNewYearsDay = Period::create(
            new DateTimeImmutable('2024-12-31 19:00:00'),
            new DateTimeImmutable('2025-01-01 00:00:00'),
        );

        $this->assertTrue($december->intersects($christMasBreak));
        $this->assertTrue($christMasBreak->intersects($december));
        $this->assertTrue($december->intersects($newYearsEveAndNewYearsDay));
    }

    public function testItReturnsFalseIfItDoesNotIntersectAPeriod(): void
    {
        $december = Period::create(
            new DateTimeImmutable('2024-12-01 00:00:00'),
            new DateTimeImmutable('2024-12-31 23:59:59'),
        );
        $november = Period::create(
            new DateTimeImmutable('2024-11-01 00:00:00'),
            new DateTimeImmutable('2024-12-01 00:00:00'),
        );

        $this->assertFalse($december->intersects($november));
    }

    public function testItReturnsTrueIfADateFallsWithinThePeriod(): void
    {
        $december = Period::create(
            new DateTimeImmutable('2024-12-01 00:00:00'),
            new DateTimeImmutable('2024-12-31 23:59:59'),
        );

        $christmasDay = new DateTimeImmutable('2024-12-25 00:00:00');
        $this->assertTrue($december->covers($christmasDay));
    }

    public function testItReturnsIfADateFallsOutsideThePeriod(): void
    {
        $december = Period::create(
            new DateTimeImmutable('2024-12-01 00:00:00'),
            new DateTimeImmutable('2024-12-31 23:59:59'),
        );

        $birthday = new DateTimeImmutable('2024-08-20 00:00:00');
        $this->assertFalse($december->covers($birthday));
        $this->assertFalse($december->covers(new DateTimeImmutable('2024-12-31 23:59:59')));
        $this->assertFalse($december->covers(new DateTimeImmutable('2024-12-01 00:00:00')));
    }

    public function testItReturnsTheInterval(): void
    {
        $start = new DateTimeImmutable('2024-12-03 00:00:00');
        $end = new DateTimeImmutable('2024-12-04 01:00:00');
        $interval = $start->diff($end);

        $period = Period::create(
            $start,
            $end,
        );

        $this->assertEquals($interval, $period->interval());
    }

    public function testItIsMoveableToASpecificDate(): void
    {
        $start = new DateTimeImmutable('2024-09-03 09:20:00');
        $end = new DateTimeImmutable('2024-09-03 10:00:00');

        $period = Period::create(
            $start,
            $end,
        );
        $diff = $period->interval();
        $newStart = new DateTimeImmutable('2024-10-03 09:25:00');

        $movedPeriod = $period->move($newStart);

        $this->assertEquals($newStart, $movedPeriod->start);
        $this->assertEquals($newStart->add($diff), $movedPeriod->end);
    }

    public function testItIsMoveableByADuration(): void
    {
        $start = new DateTimeImmutable('2024-08-15 19:30:00');
        $end = new DateTimeImmutable('2024-08-15 22:30:00');

        $period = Period::create(
            $start,
            $end,
        );
        $diff = $period->interval();

        $newStart = new DateInterval('P7D');
        $newStart->invert = 1;

        $expectedStart = new DateTimeImmutable('2024-08-08 19:30:00');
        $movedPeriod = $period->move($newStart);
        $this->assertEquals($expectedStart, $movedPeriod->start);
        $this->assertEquals($movedPeriod->start->add($diff), $movedPeriod->end);
    }

    public function testItIsRecurrableToAGivenEndDate(): void
    {
        $start = new DateTimeImmutable('2024-03-01 19:30:00');
        $end = new DateTimeImmutable('2024-03-01 22:30:00');
        $recurUntil = new DateTimeImmutable('2024-03-30 22:30:00');
        $weekly = new DateInterval('P1D');
        $period = Period::create(
            $start,
            $end,
        );
        $recurrence = $period;
        $periods = 0;

        foreach ($period->recurUntil($weekly, $recurUntil, true) as $recurringPeriod) {
            $this->assertEquals(
                $recurrence->start->add($weekly),
                $recurringPeriod->start,
            );
            $recurrence = $recurringPeriod;
            $periods++;
        }

        $this->assertSame(29, $periods);

        $this->assertEquals(
            $recurUntil,
            $recurrence->end,
        );

        $periods = 0;
        $recurrence = $period;
        foreach ($period->recurUntil($weekly, $recurUntil) as $recurringPeriod) {
            $this->assertEquals(
                $recurrence->start->add($weekly),
                $recurringPeriod->start,
            );
            $recurrence = $recurringPeriod;
            $periods++;
        }

        $this->assertSame(29, $periods);

        $this->assertEquals(
            $recurUntil,
            $recurrence->end,
        );
    }

    public function testItIsCanBeExclusivelyRecurring(): void
    {
        $start = new DateTimeImmutable('2024-03-01 19:30:00');
        $end = new DateTimeImmutable('2024-03-01 22:30:00');

        $recurUntil = new DateTimeImmutable('2024-03-30 22:30:00');

        $weekly = new DateInterval('P1D');

        $period = Period::create(
            $start,
            $end,
        );
        $recurrence = $period;
        $periods = 0;
        foreach ($period->recurUntil($weekly, $recurUntil, false) as $recurringPeriod) {
            $this->assertEquals(
                $recurrence->start->add($weekly),
                $recurringPeriod->start,
            );
            $recurrence = $recurringPeriod;
            $periods++;
        }

        $this->assertSame(28, $periods);

        $this->assertEquals(
            new DateTimeImmutable('2024-03-29 22:30:00'),
            $recurrence->end,
        );
    }
}
