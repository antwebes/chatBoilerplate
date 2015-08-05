Feature: I need to be able to register as a user

  @register @part_from_index
  Scenario: Register as an non loged in user
    Given I am on "http://boilerplatele.local/app_dev.php"
     When I click on the element with css "data-behat" and "register"
      And I fill in "user_registration_email_first" with "anemail@address.com"
      And I fill in "user_registration_email_second" with "anemail@address.com"
      And I fill in "user_registration_username" with "ausername"
      And I fill in "user_registration_plainPassword_first" with "mysuperpass"
      And I fill in "user_registration_plainPassword_second" with "mysuperpass"
      And I select "1" from "user_registration_birthday_month"
      And I select "1" from "user_registration_birthday_day"
      And I select "1990" from "user_registration_birthday_year"
      And I check "user_registration_terms_and_conditions"
      And I press "register"
      And I press "No por ahora"
     Then The "data-behat" with tag "register-success" exist