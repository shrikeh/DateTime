Feature:  So that I can correctly correctly schedule time periods
          As a software engineer
          I want to know if periods intersect with one another

  Scenario: Period does not intersect with another period.
    Given that I have a Period beginning "2024-05-01 09:30:00" and ending "2024-05-01 10:00:00"
    And I have another Period beginning "2024-05-02 09:30:00" and ending "2024-05-02 10:00:00"
    When I compare the Periods
    Then I see they do not intersect.

  Scenario: Period intersects with another period.
    Given that I have a Period beginning "2024-05-01 09:30:00" and ending "2024-05-01 10:00:00"
    And I have another Period beginning "2024-04-02 09:30:00" and ending "2024-05-02 10:00:00"
    When I compare the Periods
    Then I see they do intersect.
