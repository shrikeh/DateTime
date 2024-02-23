<?php

declare(strict_types=1);

namespace Tests\Unit\Period;

use DateInterval;
use Shrikeh\DateTime\Period\DurationDateInterval;
use PHPUnit\Framework\TestCase;

final class DurationDateIntervalTest extends TestCase
{
    public function testItIsStringable(): void
    {
        $dateSInterval = new DateInterval('P7DT3H25M12S');
        $durationDateInterval = DurationDateInterval::fromDateInterval($dateSInterval);

        $this->assertSame('P0000-00-07T03:25:12.', (string) $durationDateInterval);
    }

    public function testItNormalizesADateInterval(): void
    {
        $dateSInterval = new DateInterval('P7DT3H25M12S');

        $durationDateInterval = DurationDateInterval::normalize($dateSInterval);

        $this->assertInstanceOf(DurationDateInterval::class, $durationDateInterval);

        $this->assertSame($durationDateInterval, DurationDateInterval::normalize($durationDateInterval));
    }
}
