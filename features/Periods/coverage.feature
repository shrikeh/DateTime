Feature:  So that I can correctly correctly schedule time periods
  As a software engineer
  I want to know if a date time is within a period

  Scenario: Datetime is not within a period.
    Given that I have a Period beginning "2024-05-01 09:30:00" and ending "2024-05-01 10:00:00"
    And I have a datetime of "2024-05-02 09:30:00"
    When I check if the datetime is within the period
    Then I see it is not.
