<?php

declare(strict_types=1);

namespace Tests\Unit;

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
}