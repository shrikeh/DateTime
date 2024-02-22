<?php

declare(strict_types=1);

namespace Tests\Functional\Context;

use Behat\Behat\Context\Context;

use DateTime;
use DateTimeImmutable;
use PHPUnit\Framework\Assert;
use Shrikeh\DateTime\Period;

final readonly class Periods implements Context
{

    private Period $firstPeriod;
    private Period $secondPeriod;

    private DateTimeImmutable $test;

    private bool $result;

    /**
     * @Given I have a datetime of :datetime
     */
    public function iHaveADatetimeOf(string $datetime): void
    {
        $this->test = new DateTimeImmutable($datetime);
    }

    /**
     * @Given that I have a Period beginning :start and ending :end
     */
    public function thatIHaveAPeriodBeginningAndEnding(string $start, string $end): void
    {
        $this->firstPeriod = Period::create(new DateTime($start), new DateTime($end));
    }

    /**
     * @Given I have another Period beginning :start and ending :end
     */
    public function iHaveAnotherPeriodBeginningAndEnding(string $start, string $end): void
    {
        $this->secondPeriod = Period::create(new DateTime($start), new DateTime($end));
    }

    /**
     * @When I compare the Periods
     */
    public function iCompareThePeriods(): void
    {
       $this->result = $this->firstPeriod->intersects($this->secondPeriod);
    }

    /**
     * @When I check if the datetime is within the period
     */
    public function iCheckIfTheDatetimeIsWithinThePeriod(): void
    {
        $this->result = $this->firstPeriod->covers($this->test);
    }

    /**
     * @Then I see they do not intersect.
     */
    public function iSeeTheyDoNotIntersect(): void
    {
        Assert::assertFalse($this->result);
    }


    /**
     * @Then I see they do intersect.
     */
    public function iSeeTheyDoIntersect(): void
    {
        Assert::assertTrue($this->result);
    }

    /**
     * @Then I see it is not.
     */
    public function iSeeItIsNot(): void
    {
        Assert::assertFalse($this->result);
    }
}
