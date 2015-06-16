Feature: I need to be able to login

  @login
  Scenario: Login
    Given I am on "http://boilerplatele.local/app_dev.php"
     When I click "Acceder con tu cuenta"
      And I fill in "username" with "ausername"
      And I fill in "password" with "mysuperpassword"
      And I press "button-submit-login"
     Then I should see "Bienvenido ausername!"