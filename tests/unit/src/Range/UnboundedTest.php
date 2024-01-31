<?php

declare(strict_types=1);

namespace Tests\Unit\Range;

use DateTime;
use DateTimeImmutable;
use DateTimeInterface;
use PHPUnit\Framework\TestCase;
use Shrikeh\DateTime\Exception\Message;
use Shrikeh\DateTime\Period;
use Shrikeh\DateTime\Range;
use Shrikeh\DateTime\Range\Unbounded;
use Shrikeh\DateTime\Range\Exception\InsufficientDatesToCreatePeriod;

final class UnboundedTest extends TestCase
{
    public function testItSortsDateTimes(): void
    {
        $sevenDays = new DateTime('+7 days');
        $now = new DateTimeImmutable();
        $anHourAgo = new DateTimeImmutable('-1 hour');

        $range = Range::unbounded($sevenDays, $now, $anHourAgo);

        $expected = array_map(static function (DateTimeInterface $dateTime): string {
            return $dateTime->format(DATE_ATOM);
        }, [$anHourAgo, $now, $sevenDays]);

        $ordered = array_map(static function (DateTimeInterface $dateTime): string {
            return $dateTime->format(DATE_ATOM);
        }, iterator_to_array($range()));

        $this->assertSame($expected, $ordered);
    }

    public function testItCanAcceptNewDateTimes(): void
    {
        $sevenDays = new DateTime('+7 days');
        $now = new DateTimeImmutable();
        $anHourAgo = new DateTimeImmutable('-1 hour');

        $range = Unbounded::fromDateTimes($sevenDays, $now, $anHourAgo);
        $lastWeek = new DateTimeImmutable('-1 week');
        $newRange = $range->add($lastWeek);

        $this->assertSame(
            $lastWeek->format(DATE_ATOM),
            $newRange->dateTimes[0]->format(DATE_ATOM)
        );
        $this->assertSame(
            $sevenDays->format(DATE_ATOM),
            $newRange->latest()->format(DATE_ATOM)
        );
    }

    public function testItReturnsTheFirstDateTime()
    {
        $sevenDays = new DateTime('+7 days');
        $now = new DateTimeImmutable();
        $anHourAgo = new DateTimeImmutable('-1 hour');

        $range = Unbounded::fromDateTimes($sevenDays, $now, $anHourAgo);

        $this->assertSame($anHourAgo->format(DATE_ATOM), $range->earliest()->format(DATE_ATOM));
    }

    public function testItReturnsTheLastDateTime()
    {
        $sevenDays = new DateTime('+7 days');
        $now = new DateTimeImmutable();
        $anHourAgo = new DateTimeImmutable('-1 hour');

        $range = Unbounded::fromDateTimes($sevenDays, $now, $anHourAgo);

        $this->assertSame($sevenDays->format(DATE_ATOM), $range->latest()->format(DATE_ATOM));
    }

    public function testItCreatesAPeriod(): void
    {
        $sevenDays = new DateTime('+7 days');
        $anHourAgo = new DateTimeImmutable('-1 hour');
        $range = Unbounded::fromDateTimes($sevenDays, $anHourAgo);
        $period = Period::create($sevenDays, $anHourAgo);
        $this->assertEquals($period, $range->period());
    }

    public function testItThrowsAnExceptionIfThereAreInsufficientDatesToCreateAPeriod()
    {
        $range = Unbounded::fromDateTimes(new DateTimeImmutable());
        $this->expectExceptionObject(new InsufficientDatesToCreatePeriod($range));
        $this->expectExceptionMessage(Message::INSUFFICIENT_DATES_FOR_PERIOD->msg(1));
        $range->period();
    }
}
