Feature: Test to page index and check this template has footer

  @pablo @part_from_index
  Scenario: Check exist footer
    Given I am on "http://boilerplatele.local/app_dev.php"
     Then I should see the element footer