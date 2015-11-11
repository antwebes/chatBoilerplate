@view_popular_photos @part_from_index
Feature: View popular photos
  Check page popular photos

  Scenario: View popular photos
    Given I am on "http://boilerplatele.local/app_dev.php"
     When I click on the element with css "data-behat" and "popular-photos"
     Then the API url "/api/photos?limit=30&offset=0&filters=number_votes_greater_equal%3D3&order=score%3Ddesc" should have been called
      And I should see 100 photos