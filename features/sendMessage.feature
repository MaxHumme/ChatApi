Feature: Sending a message.
  As a user
  I want to send a message to another user
  So we can communicate

  Background:
    Given there are users:
    | username   | auth_token | first_name | last_name  |
    | darthvader | 123456     | Darth      | Vader      |
    | thehoff    | qwerty     | David      | Hasselhoff |

  Scenario: Send a message
    Given I am authenticated as "darthvader"
    And I send a POST request to "api/v1/thehoff/messages" with json:
    """
    {
      "message": "David, I am your father."
    }
    """
    Then the response code should be 201
    And the response should contain "links"
    And the response should contain "rel"
    And the response should contain "href"
    Given I am authenticated as "thehoff"
    And I send a GET request to "api/v1/my/messages"
    Then the response code should be 200
    And the response should contain "David, I am your father."

  Scenario: Sending a message to yourself is not allowed
    Given I am authenticated as "darthvader"
    And I send a POST request to "api/v1/darthvader/messages" with json:
    """
    {
      "message": "This should not work."
    }
    """
    Then the response code should be 403
    And the response should contain "message"
    And the response should contain "You may not send a message to yourself."
