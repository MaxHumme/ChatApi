Feature: Getting your messages.
  As a user
  I want to get my messages
  So I can read them

  Background:
    Given there are users:
      | username   | auth_token | first_name | last_name  |
      | darthvader | 123456     | Darth      | Vader      |
      | thehoff    | qwerty     | David      | Hasselhoff |
    And each user has 25 messages

  Scenario: Get your messages
    Given I am authenticated as "darthvader"
    When I send a GET request to "api/v1/my/messages"
    Then the response code should be 200
    And the response should contain "messages"
    And the response should contain "author"
    And the response should contain "sentAt"
