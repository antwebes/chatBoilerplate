@view_user @part_from_index
Feature: View User data
  Check page show user

  @view_user_profile @view_users
  Scenario: View user profile with button chat
    Given I am on "http://boilerplatele.local/app_dev.php"
     When I click "Usuarios"
     Then the API url "/api/users?limit=30&offset=0&filters=language%3Des&order=hasProfilePhoto%3Ddesc%2ClastLogin%3Ddesc" should have been called
      And the API url "/api/users?limit=25&offset=0&filters=language%3Des%2Coutstanding%3D1" should have been called
      And I click "Emily"
      And I should see the value "btn_chat_user" into attribute "data-behat"

  @view_my_profile
  Scenario: View my profile with button edit profile
    Given I am logedin
      And I should see "Mi cuenta"
      And I click on the element with css "data-behat" and "user-greeting"
     Then I should see "Perfil de ausername"
      And I should see the value "edit_profile" into attribute "data-behat"
      And I should see the value "share-buttons" into attribute "data-behat"