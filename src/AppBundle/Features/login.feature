Feature: I need to be able to login

  @login @part_from_index @page_welcome
  Scenario: Login
    Given I am on "http://boilerplatele.local/app_dev.php"
     When I click "Iniciar sesión"
      And I fill in "username" with "ausername"
      And I fill in "password" with "mysuperpassword"
      And I press "button-submit-login"
     Then I should see "Bienvenido ausername"
     
   @login @part_from_index @page_welcome
   Scenario: See page welcome
    Given I am on "http://boilerplatele.local/app_dev.php"
     When I click "Iniciar sesión"
      And I fill in "username" with "ausername"
      And I fill in "password" with "mysuperpassword"
      And I press "button-submit-login"
     Then I should see "Aquí verás la actividad de nuestra comunidad"