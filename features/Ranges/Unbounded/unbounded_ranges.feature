Feature:
  So that I can store and compare dates and times
  As a software engineer
  I want to store dates and times in order

  Scenario: Unbounded range of unordered dates
    Given that I have the following dates and times:
    | Date       | Time     |
    | 2023-08-20 | 09:30:00 |
    | 2024-08-16 | 12:30:00 |
    | 2023-04-01 | 11:30:10 |
    | 2023-08-20 | 09:31:00 |
    When I check the range
    Then I see the following date times:
      | Date       | Time     |
      | 2023-04-01 | 11:30:10 |
      | 2023-08-20 | 09:30:00 |
      | 2023-08-20 | 09:31:00 |
      | 2024-08-16 | 12:30:00 |

  Scenario: Unbounded range can have no dates
    Given I have not added any dates
    When I check the range
    Then the earliest datetime is empty
    And the latest datetime is empty
