@view_user
Feature: Edit provile data
  I need to be able to edit profile data

  @edit_user_profile @edit_user_profile_success
  Scenario: Edit profile with success
    Given I am logedin
     When I click on the element with css "data-behat" and "change-profile"
      And I click "Editar perfil"
      And I fill in "social_profile_youWant" with "algo querere"
      And I fill in "social_profile_about" with "algo sobre min"
      And I select "4" from "social_profile_birthday_month"
      And I select "6" from "social_profile_birthday_day"
      And I select "1990" from "social_profile_birthday_year"
      And I press "Enviar"
     Then The "data-behat" with tag "msg-success" exist
     
  @edit_user_profile @edit_user_profile_seeking_empty
  Scenario: Edit profile with seeking empty
    Given I am logedin
     When I click on the element with css "data-behat" and "change-profile"
      And I click "Editar perfil"
      And I fill in "social_profile_youWant" with "text of you want"
      And I fill in "social_profile_about" with "algo sobre min"
      And I select "Escoge una opción" from "social_profile[seeking]"
      And I select "7" from "social_profile_birthday_month"
      And I select "10" from "social_profile_birthday_day"
      And I select "1994" from "social_profile_birthday_year"
      And I press "Enviar"
     Then I should see "not contain extra fields"
     
  Scenario: Go to page profile, user need is loged
     When I go to "perfil"
     Then I should be on "usuario/login"
     
  Scenario: Go to page profile, user need is loged
     Then I should be on "usuario/login"
  
  Scenario: Go to page profile, user need is loged
     When I go to "perfil/actualizar/foto"
     Then I should be on "usuario/login"   
