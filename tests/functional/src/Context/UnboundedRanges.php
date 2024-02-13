<?php

declare(strict_types=1);

namespace Tests\Functional\Context;

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\TableNode;
use DateTimeImmutable;
use PHPUnit\Framework\Assert;
use Shrikeh\DateTime\Range\Unbounded;
use SplObjectStorage;

final readonly class UnboundedRanges implements Context
{
    private Unbounded $unbounded;

    private array $originals;

    /**
     * @Given that I have the following dates and times:
     */
    public function thatIHaveTheFollowingDatesAndTimes(TableNode $dateTimes)
    {
        $originals = [];

        foreach ($dateTimes->getHash() as $dateTime) {
            $originals[] =
                DateTimeImmutable::createFromFormat(
                    'Y-m-d H:i:s',
                    sprintf('%s %s', $dateTime['Date'], $dateTime['Time']),
                );
        }

        $this->originals = $originals;
    }

    /**
     * @When I check the range
     */
    public function iCheckTheRange()
    {
        $this->unbounded = Unbounded::fromDateTimes(... $this->originals);
    }

    /**
     * @Then I see the following date times:
     */
    public function iSeeTheFollowingDateTimes(TableNode $expectedDateTimes)
    {
        $actual = [];
        foreach ($this->unbounded->__invoke() as $actualDateTime) {
            $actual[] = [
                'Date' => $actualDateTime->format('Y-m-d'),
                'Time' => $actualDateTime->format('H:i:s')
            ];
        }
        foreach ($expectedDateTimes->getHash() as $index => $expected) {
            Assert::assertSame($expected, $actual[$index]);
        }
    }

    /**
     * @Given I have not added any dates
     */
    public function iHaveNotAddedAnyDates()
    {
        $this->originals = [];
    }

    /**
     * @Then the earliest datetime is empty
     */
    public function theEarliestDatetimeIsEmpty()
    {
        Assert::assertNull($this->unbounded->earliest());
    }

    /**
     * @Then the latest datetime is empty
     */
    public function theLatestDatetimeIsEmpty()
    {
        Assert::assertNull($this->unbounded->latest());
    }
}
