Feature: Manage backend user permissions
  @javascript
  Scenario: As an admin I want to see user permissions
    Given I am logged in as "admin"
    When I am on "/bolt/users"
    Then I should see "edit users and their permissions"
    And I should see at least 2 "tr" elements
    And I should see "admin@example.org" in the "tr:nth-child(1) > td:nth-child(4)" element
    And I should see "ROLE_ADMIN" in the "tr:nth-child(1) > td:nth-child(5)" element
