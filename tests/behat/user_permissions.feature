Feature: Manage backend user permissions

  @javascript
  Scenario: As an admin I want to see user permissions
    Given I am logged in as "admin"
    When I am on "/bolt/users"
    Then I should see "edit users and their permissions"
    And I should see at least 2 "tr" elements
    And I should see "admin@example.org" in the "tr:nth-child(1) > td:nth-child(4)" element
    And I should see "ROLE_ADMIN" in the "tr:nth-child(1) > td:nth-child(5)" element

  Scenario: As an admin I want to create another admin
    Given I am logged in as "admin"
    When I am on "/bolt/users"
    And I follow "Add User"

    Then I should be on "/bolt/user-edit/0"
    And I should see "New User" in the ".admin__header--title" element

    When I fill in the following:
      | username | ivo_admin |
      | display_name | Ivo The Admin |
      | password | admin%2 |
      | email | ivo@example.org |

    And I click "#multiselect-locale > div > div.multiselect__content-wrapper > ul > li:nth-child(1) > span"

    When I scroll "#multiselect-roles > div > div.multiselect__select" into view
    Then I click "#multiselect-roles > div > div.multiselect__select"
    And I click "#multiselect-roles > div > div.multiselect__content-wrapper > ul > li:nth-child(1) > span"

    When I scroll "[type='submit']" into view
    Then I press "Save changes"

   @javascript
   Scenario: As an admin I want to edit another user
     Given I am logged in as "admin"
     When I am on "/bolt/users"
     And I click "table > tbody > tr:nth-child(2) > td:nth-child(8) > a"

     Then I should be on "/bolt/user-edit/2"
     And the "username" field should contain "henkie"

     When I fill in "display_name" with "Gekke Henkie The Second"
     When I scroll "[type='submit']" into view
     Then I press "Save changes"

     Then I should be on "/bolt/users"
     And I should see "Gekke Henkie The Second"