@view_user
Feature: Edit provile data
  I need to be able to edit profile data

  @edit_user_profile
  Scenario: View a users photo as loged in user
    Given I am logedin
     When I click "Cambiar perfil"
      And I click "Editar perfil"
      And I fill in "social_profile_youWant" with "algo querere"
      And I fill in "social_profile_about" with "algo sobre min"
      And I select "4" from "social_profile_birthday_month"
      And I select "6" from "social_profile_birthday_day"
      And I select "1990" from "social_profile_birthday_year"
      And I press "Enviar"
     Then I should see "Tu perfil ha sido actualizado con éxito"
