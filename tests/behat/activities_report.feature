@report @report_activities @core_reportbuilder @javascript
Feature: Activities report source in Report builder
  In order to report graded activities
  As an admin
  I need to create a custom report using the Activities datasource

  Scenario: Create custom report from Activities source with default setup
    Given the following "courses" exist:
      | fullname     | shortname |
      | Course Alpha | C1        |
    And the following "users" exist:
      | username | firstname | lastname |
      | student1 | Student   | One      |
    And the following "course enrolments" exist:
      | user     | course | role    |
      | student1 | C1     | student |
    And the following "activities" exist:
      | activity | course | name           | assignsubmission_onlinetext_enabled | grade |
      | assign   | C1     | My assignment  | 1                                   | 100   |
    And I log in as "admin"
    And I change window size to "large"
    When I navigate to "Reports > Report builder > Custom reports" in site administration
    And I click on "New report" "button"
    And I set the following fields in the "New report" "dialogue" to these values:
      | Name                  | Activities grades |
      | Report source         | Activities        |
      | Include default setup | 1                 |
    And I click on "Save" "button" in the "New report" "dialogue"
    Then I should see "Activities grades"
    And I should see "Full name" in the "[data-region='report-table']" "css_element"
    And I should see "Activity name" in the "[data-region='report-table']" "css_element"
    And I should see "Final grade" in the "[data-region='report-table']" "css_element"
    And I click on "Close 'Activities grades' editor" "button"
    And the following should exist in the "Reports list" table:
      | Name              | Report source |
      | Activities grades | Activities    |
