@view_user @part_from_index
Feature: View User data
  In order to find interest people to meet, I need to list them and view their profiles

  @view_user_list @view_users
  Scenario: List users
  Given I am on "http://boilerplatele.local/app_dev.php"
   When I click "Usuarios"
   Then the API url "/api/users?limit=30&offset=0&filters=language%3Des" should have been called
    And I should see that there are 2 pages
    And I should see the following users with there profile photo and link to the profile:
      | Emily   | Rothville  | http://api.chateagratis.local/uploads/2_medium.jpg | /usuarios/emily-2   |
      | Selena4 | West Cowes | http://api.chateagratis.local/uploads/3_medium.jpg | /usuarios/selena4-3 |
      | Mia     | Ouzinkie   | http://api.chateagratis.local/uploads/4_medium.jpg | /usuarios/mia-4     |

  @view_user_profile @view_users
  Scenario: View user profile
    Given I am on "http://boilerplatele.local/app_dev.php"
     When I click "Usuarios"
     Then the API url "/api/users?limit=30&offset=0&filters=language%3Des" should have been called
      And the API url "/api/users?limit=25&offset=0&filters=outstanding%3D1" should have been called
      And I click "Emily"
      And I should see the following profile information:
        | Nombre 			| Emily     |
        | Localización      | Rothville |
        | Edad        |           | #ponemos age vacio para que no peten los test cada año
        | Sexo	            | Mujer     |
        | Estoy buscando	| Mujeres   |

  @view_user_profile @view_users
  Scenario: To view users photos I need to login
    Given I am on "http://boilerplatele.local/app_dev.php"
     When I click "Usuarios"
      And I click "Emily"
     When I click "Fotos"
     Then I should be on "/usuario/login"

  @view_user_profile @view_users
  Scenario: View users photos as loged in user
    Given I am logedin
      #And I am on "http://boilerplatele.local/app_dev.php"
     When I click "Usuarios"
      And I click "Emily"
      And I click "Fotos"
     Then I should be on "/usuarios/2/fotos"
      And I should see 19 photos

  @view_user_profile @view_users
  Scenario: View a users photo as loged in user
    Given I am logedin
      #And I am on "http://boilerplatele.local/app_dev.php"
     When I click "Usuarios"
      And I click "Emily"
      And I click "Fotos"
      And I click on the first photo
     Then I should be on "/2014/02/18/53031792da81a_large.jpg"
