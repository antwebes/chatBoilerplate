<?php
namespace AppBundle\Features\Context;

use Ant\Bundle\GuzzleFakeServer\Behat\Context\Context as BaseContext;
use AppBundle\Provider\Configuration;
use Behat\Gherkin\Node\TableNode;
use Behat\Mink\Exception\ExpectationException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\KernelInterface;

class FeatureContext extends BaseContext
{
    protected $kernel;

    /**
     * Sets HttpKernel instance.
     * This method will be automatically called by Symfony2Extension ContextInitializer.
     *
     * @param KernelInterface $kernel
     */
    public function setKernel(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
        parent::setKernel($kernel);
    }

    protected function doInitFakeServer()
    {
        $cachedServiseces = array('antwebes_client', 'antwebs_chateasecure.guzzle_client', 'antwebes_client_auth');
        $container = $this->kernel->getContainer();

        // we need to cache the guzzle client services so we dont generate them evevy time we ask for them and so not loosing the listeners of the faker
        $container->setServicesToCache($cachedServiseces);

        parent::doInitFakeServer();

        $this->fakeServerMappings->addPostResource(
            '/oauth/v2/token',
            'fixtures/login/success_client_login.json',
            200,
            array(
                "grant_type" => "client_credentials",
                "client_id" => $this->kernel->getContainer()->getParameter("chatea_client_id"),
                "client_secret" => $this->kernel->getContainer()->getParameter("chatea_secret_id")
            )
        );
    }

    /**
     * @BeforeScenario @register
     */
    public function beforeRegister()
    {
        parent::doInitFakeServer();

        $container = $this->kernel->getContainer();
        $parameters = Configuration::loadYml(new Request(),$container->getParameter('affiliate_dir_path'));

        $this->fakeServerMappings->addPostResource(
            '/oauth/v2/token',
            'fixtures/login/success_register_login.json',
            200,
            array(
                "grant_type" => "password",
                "username" => "ausername",
                "password" => "mysuperpass",
                "client_id" =>  $container->getParameter("chatea_client_id"),
                "client_secret" => $container->getParameter("chatea_secret_id")
            )
        );

        $this->fakeServerMappings->addPostResource(
            '/api/register',
            'fixtures/users/register.json',
            200,
            array(
                'user_registration' => array(
                    'email' => 'anemail@address.com',
                    'username' => 'ausername',
                    'plainPassword' => array(
                        'first' => 'mysuperpass',
                        'second' => 'mysuperpass'
                    ),
                    'client' => (string)$parameters['client'],
                    'ip' => '127.0.0.1',
                    'language' => 'es'
                )
            )
        );

//         $this->fakeServerMappings->addGetResource(
//             '/api/users/2',
//             'fixtures/users/user_recent_register.json'
//         );
        
        //Tenemos que hacer esta llamada, porque ahora para saltar el registro, reemplazamos esta variable por el id de usuario, pero lo hacemos por javascript por eso no se llega a cambiar.
        $this->fakeServerMappings->addGetResource(
        		'/api/users/__userId__',
        		'fixtures/users/user_recent_register.json'
        		);
    }

    /**
     * @BeforeScenario @login
     */
    public function beforeLogin()
    {
        $this->doInitFakeServer();

        $this->fakeServerMappings->addPostResource(
            '/oauth/v2/token',
            'fixtures/login/success_register_login.json',
            200,
            array(
                "grant_type" =>  "password",
                "client_id" => $this->kernel->getContainer()->getParameter("chatea_client_id"),
                "client_secret" => $this->kernel->getContainer()->getParameter("chatea_secret_id"),
                "username" => "ausername",
                "password" => "mysuperpassword"
            )
        );
        
        //Here We need fake this call, because we show widget user session in navbar
        $this->fakeServerMappings->addGetResource(
        		'/api/users/2',
        		'fixtures/users/user.json'
        		);
    }

    /**
     * @BeforeScenario @view_user_list
     */
    public function beforeViewUserList()
    {
        $this->doInitFakeServer();

        $this->fakeServerMappings->addGetResource(
            '/api/users?limit=30&offset=0&filters=language%3Des&order=hasProfilePhoto%3Ddesc%2ClastLogin%3Ddesc',
            'fixtures/users/users_limit.json'
        );
        
        $this->fakeServerMappings->addGetResource(
        		'/api/channels?filter=language%3Des&order=fans%3Ddesc&limit=10&offset=0',
        		'fixtures/users/users_limit.json'
        );
    }
    
    /**
     * @BeforeScenario @page_welcome
     */
    public function beforePageWelcome()
    {
    	//para page welcome
    	$this->fakeServerMappings->addGetResource(
    	'/api/me',
    	'fixtures/users/user_logued.json'
    			);
    	
    	$this->fakeServerMappings->addGetResource(
    			'/api/users/2/visitors?limit=3',
    			'fixtures/users/visitors.json'
    			);
    }
    
    /**
     * @BeforeScenario @part_from_index
     */
    public function beforeViewIndexList()
    {
    	$this->doInitFakeServer();
    
    	//para index
	    $this->fakeServerMappings->addGetResource(
	    		'/api/channels?filter=&order=&limit=10&offset=0',
	    		'fixtures/channels/counter_channels_index.json'
	    );
	    
	    //para index, llamada de recuperar a los usuarios	    
	    $this->fakeServerMappings->addGetResource(
	    		'/api/users?limit=12&offset=0&filters=has_profile_photo%3D1',
	    		'fixtures/users/counter_users_index.json'
	    		);
	    
	    $this->fakeServerMappings->addGetResource(
	    		'/api/users?limit=30&offset=0',
	    		'fixtures/users/counter_users_index.json'
	    );
	    
	    $this->fakeServerMappings->addGetResource(
	    		'/api/users?limit=30&offset=0&filters=language%3Des%2Chas_profile_photo%3D1&order=randomly%3Dasc',
	    		'fixtures/users/users_limit.json'
	    );
	    //fin de index
    }

    /**
     * @BeforeScenario @view_users
     */
    public function beforeUsersView()
    {
    	$this->beforeViewUserList();
    
    	$this->fakeServerMappings->addGetResource(
    			'/api/users?limit=25&offset=0&filters=language%3Des%2Coutstanding%3D1',
    			'fixtures/users/users_outstanding.json'
    			);
    }
    
    /**
     * @BeforeScenario @view_my_profile
     */
    public function beforeViewMyProfile()
    {   
    	$this->doInitFakeServer();
    	
    	$this->fakeServerMappings->addGetResource(
    			'/api/users/2',
    			'fixtures/users/user_logued.json'
    			);
    
    	
    	$this->fakeServerMappings->addGetResource(
    			'/api/users/2/visitors?limit=3',
    			 'fixtures/users/visitors.json'
    			);
    	
    	//RightBar with list channels in show user
        $this->fakeServerMappings->addGetResource(
        		'/api/channels?filter=language%3Des&order=fans%3Ddesc&limit=10&offset=0',
        		'fixtures/users/users_limit.json'
        );
    }

    /**
     * @BeforeScenario @view_popular_photos
     */
    public function beforeViewPopularPhotos()
    {
        $this->doInitFakeServer();

        $this->fakeServerMappings->addGetResource(
            '/api/photos?limit=30&offset=0&filters=number_votes_greater_equal%3D3&order=score%3Ddesc',
            'fixtures/photos/popularphotos.json'
        );
    }
    
    /**
     * @BeforeScenario @view_user_profile
     */
    public function beforeUserProfile()
    {
        $this->beforeViewUserList();

        $this->fakeServerMappings->addGetResource(
            '/api/users/emily',
            'fixtures/users/user.json'
        );

        $this->fakeServerMappings->addGetResource(
            '/api/users/2',
            'fixtures/users/user.json'
        );

        $this->fakeCallsOfUserProfile();
    }

    /**
     * @BeforeScenario @view_user_with_no_profile
     */
    public function beforeUserNoProfile()
    {
        $this->beforeViewUserList();

        $this->fakeServerMappings->addGetResource(
            '/api/users/emily',
            'fixtures/users/user_no_profile.json'
        );

        $this->fakeServerMappings->addGetResource(
            '/api/users/2',
            'fixtures/users/user_no_profile.json'
        );

        $this->fakeCallsOfUserProfile();
    }

    private function fakeCallsOfUserProfile()
    {
        //RightBar with list channels in show user
        $this->fakeServerMappings->addGetResource(
        		'/api/channels?filter=language%3Des&order=fans%3Ddesc&limit=10&offset=0',
        		'fixtures/users/users_limit.json'
        );
        $this->fakeServerMappings->addGetResource(
        		'/api/users/2/photos?limit=30&offset=0',
        		'fixtures/users/user_fotos.json'
        );
        
        $this->fakeServerMappings->addGetResource(
            '/api/users/2/channels?limit=25&offset=0',
            'fixtures/users/user_channels.json'
        );
        $this->fakeServerMappings->addGetResource(
            '/api/channels/1/fans?limit=4&offset=0&filters=has_profile_photo%3D1',
            'fixtures/channels/channels_fans.json'
        );
        $this->fakeServerMappings->addGetResource(
            '/api/channels/2/fans?limit=4&offset=0&filters=has_profile_photo%3D1',
            'fixtures/channels/channels_fans.json'
        );
        $this->fakeServerMappings->addGetResource(
            '/api/channels/100/fans?limit=4&offset=0&filters=has_profile_photo%3D1',
            'fixtures/channels/channels_fans.json'
        );

        $this->fakeServerMappings->addGetResource(
            '/api/users/2/photos?limit=25&offset=0',
            'fixtures/users/user_fotos.json'
        );

        $this->fakeServerMappings->addGetResource(
            '/api/photos/495',
            'fixtures/users/user_foto.json'
        );

        $this->fakeServerMappings->addGetResource(
            '/api/users/2/visitors?limit=3',
            'fixtures/users/visitors.json'
        );
    }

    /**
     * @BeforeScenario @reset_password
     */
    public function beforePassword()
    {
        parent::doInitFakeServer();

        $this->fakeServerMappings->addPostResource(
            '/resetting/send-email',
            'fixtures/users/reset_password.json',
            200,
            array(
                "username" => "anemail@address.com"
            )
        );
    }

    /**
     * @BeforeScenario @edit_user_profile
     */
    public function beforeEditUserProfile()
    {
        $this->beforeViewUserList();

        $this->fakeServerMappings->addGetResource(
            '/api/users/emily',
            'fixtures/users/user.json'
        );
        $this->fakeServerMappings->addGetResource(
            '/api/users/2',
            'fixtures/users/user.json'
        );
        $this->fakeServerMappings->addGetResource(
            '/api/users/2/channels?limit=25&offset=0',
            'fixtures/users/user_channels.json'
        );
        $this->fakeServerMappings->addGetResource(
            '/api/channels/1/fans?limit=4&offset=0&filters=has_profile_photo%3D1',
            'fixtures/channels/channels_fans.json'
        );
        $this->fakeServerMappings->addGetResource(
            '/api/channels/2/fans?limit=4&offset=0&filters=has_profile_photo%3D1',
            'fixtures/channels/channels_fans.json'
        );
        $this->fakeServerMappings->addGetResource(
            '/api/channels/100/fans?limit=4&offset=0&filters=has_profile_photo%3D1',
            'fixtures/channels/channels_fans.json'
        );

        $this->fakeServerMappings->addGetResource(
            '/api/users/2/photos?limit=25&offset=0',
            'fixtures/users/user_fotos.json'
        );

        $this->fakeServerMappings->addGetResource(
            '/api/photos/495',
            'fixtures/users/user_foto.json'
        );
    }
    
    /**
     * @BeforeScenario @edit_user_profile_success
     */
    public function beforeEditUserProfileSuccess()
    {
    	$this->fakeServerMappings->addPatchResource(
    			'/api/users/2/profiles',
    			'fixtures/users/user_profile.json',
    			200,
    			array(
    					"social_profile" => array(
    							"about" => "algo sobre min",
    							"seeking" => "women",
    							"gender" => "female",
    							"youWant" => "algo querere",
    							"birthday" => "1990-04-06"
    					)
    			)
    	);
    }
    
    /**
     * @BeforeScenario @edit_user_profile_seeking_empty
     */
    public function beforeEditUserProfileSeekingEmpty()
    {
    	$this->fakeServerMappings->addPatchResource(
    			'/api/users/2/profiles',
    			'fixtures/users/error_extra_field.json',
    			400,
    			array(
    					"social_profile" => array(
    							"about" => "algo sobre min",
    							"seeking" => "",
    							"gender" => "female",
    							"youWant" => "text of you want",
    							"birthday" => "1994-07-10"
    					)
    			)
    	);
    }

    /**
     * @param ServiceMocker $mocker
     */
    public function setServiceMocker(ServiceMocker $mocker)
    {
        $this->mocker = $mocker;
    }

    /**
     * @When /^I click "([^"]*)"$/
     */
    public function iClick($value)
    {
        $element = $this->getSession()->getPage()->find('css',
            sprintf('a:contains("%s")', $value)
        );

        $element->click();
    }

    /**
     * @Given /^The tile is "([^"]*)"$/
     */
    public function theTileIs($arg1)
    {
        $session = $this->getSession(); // get the mink session
        $titleElement = $session->getPage()->find(
            'xpath',
            $session->getSelectorsHandler()->selectorToXpath('xpath', '//title')
        );
        if($titleElement == null){
            throw new ExpectationException("The title of page is not found ", $this->getSession());
        }
        if($titleElement->getText() != $arg1){
            throw new ExpectationException('The title of page is "'. $titleElement->getText(). '" expected "'. $arg1 .'"', $this->getSession());
        }
    }

    /**
     * @Given /^The meta data is:$/
     */
    public function theMetaDataIs(TableNode $table)
    {

        $session = $this->getSession(); // get the mink session

        $tableRows = $table->getRows();
        foreach($tableRows as $row){
            if(count($row) < 2){
                throw new ExpectationException("The feature for meta data is bad defined expeted table with format |<name> | content | ");
            }

            $metaNameExpected = trim($row[0]);
            $metaContentExpected = trim($row[1]);
            $metaElement = $session->getPage()->find(
                'xpath',
                $session->getSelectorsHandler()->selectorToXpath('xpath', '//meta[@name="'.$metaNameExpected. '"]')
            );
            if($metaElement == null){
                throw new ExpectationException("The header meta data with name $metaContentExpected is not defined ",$this->getSession());
            }

            if(trim($metaElement->getAttribute('content')) != $metaContentExpected){
                throw new ExpectationException('The header meta data with name="'.$metaNameExpected . '" have the content="'. $metaElement->getAttribute('content') . '" and expected ontent="'.$metaContentExpected .'"', $this->getSession());
            }
        }

    }

    /**
     * @Then /^I should see (\d+) users$/
     */
    public function iShouldSeeUsers($count)
    {
        $element = $this->findElementDataBehat('items-user', 'data-behat');

        $countElements = count($element->findAll('css', 'li'));

        if($countElements != $count){
            $message = sprintf("Expected to see %s users", $count);
            throw new ExpectationException($message, $this->getSession());
        }
    }
    
    /**
     * @Given /^I should see the value "([^"]*)" into attribute "([^"]*)"$/
     */
    public function iShouldSeeTheValueIntoAttribute($arg1, $arg2)
    {
    	return $this->findElementDataBehat($arg1, $arg2);
    }

    /**
     * find a element with dataName and attrData
     * @param string $dataName
     * @param string $attrData
     * @throws ExpectationException
     * @return Ambigous <\Behat\Mink\Element\NodeElement, NULL>
     */
    protected function findElementDataBehat($attrData, $dataName='data-behat')
    {
        $element = $this->getSession()->getPage()->find('css', '*['.$dataName.'="'.$attrData.'"]');
        if(!$element){
            $message = sprintf("Element was not found in data %s = %s ", $dataName, $attrData);
            throw new ExpectationException($message, $this->getSession());
        }
        return $element;
    }

    /**
     * @Given /^I should see that there are (\d+) pages$/
     */
    public function iShouldSeeThatThereArePages($numPages)
    {
        $element = $this->findElementDataBehat('pagination');

        $lastPage = $element->find('css', '.last a');

        if ($lastPage == null) {
            $links = $element->findAll("css", "li a");
            $lastLink = $links[count($links) - 1];

            if (trim($lastLink->getHtml()) != $numPages) {
                $message = sprintf("Expected to see %s pages", $numPages);
                throw new ExpectationException($message, $this->getSession());
            }
        } else {
            if (trim($lastPage->getHtml()) != ".." . $numPages) {
                $message = sprintf("Expected to see %s pages", $numPages);
                throw new ExpectationException($message, $this->getSession());
            }
        }
    }

    /**
     * @Given /^I should see the following users with there profile photo and link to the profile:$/
     */
    public function iShouldSeeTheFollowingUsersWithThereProfilePhotoAndLinkToTheProfile(TableNode $table)
    {
        foreach ($table->getRows() as $row) {
            list($username, $city, $photo, $profileLink) = $row;

            $userLi = $this->findUserLi($username);

            if($userLi){
                $usernameElement = $userLi->find('css', '*:contains("'.$username.'")');
                $img = $userLi->find('css', 'img[data-original="'.$photo.'"]');
                $cityElement = $userLi->find('css', '*:contains("'.$city.'")');
                $profileLinkElement = $userLi->find('css', 'a[href="'.$profileLink.'"]');

                if(!($usernameElement && $img && $cityElement && $profileLinkElement)){
                    $message = sprintf("Expected to see user %s", $username);
                    throw new ExpectationException($message, $this->getSession());
                }
            }else{
                $message = sprintf("Expected to see user li");
                throw new ExpectationException($message, $this->getSession());
            }
        }
    }

    private function findUserLi($username)
    {
        $li = $this->getSession()->getPage()->find('css', '[data-behat = items-user] li:contains("'.$username.'")');
        return $li;
    }

    /**
     * @Given /^I should see the following profile information:$/
     */
    public function iShouldSeeTheFollowingProfileInformation(TableNode $table)
    {
        foreach($table->getRows() as $row){

            list($key, $value) = $row;

            $existKey = $this->iShouldSeeTheFieldIntoData('profile-row-name', $key);
            $existValue = $this->iShouldSeeTheFieldIntoData('profile-row-value', $value);

            if(!($existKey && $existValue)){
                $message = sprintf("%s expected to to be %s", $key, $value);
                throw new ExpectationException($message, $this->getSession());
            }
        }

    }

    /**
     * En el show user, tenemos un cover, y luego un listado, comprobamos el texto del cover y luego los elementos del listado
     * @Given /^I should see the cover "([^"]*)" and container "([^"]*)" and the table:$/
     */
    public function iShouldSeeTheCoverAndContainerAndTheTable($arg1, $arg2, TableNode $table)
    {
        $container = $this->theWithTagExist("data-behat", $arg1);
        $this->iShouldSeeThisTableWithData($table, $arg2, "name-channel");
    }

    /**
     * @Given /^I should see (\d+) photos$/
     */
    public function iShouldSeePhotos($expectedNumPhotos)
    {
        $elements = $this->getSession()->getPage()->findAll('css', '[data-behat="photos"] figure');

        if(count($elements) != $expectedNumPhotos){
            $message = sprintf("Expected to to be %s photos but got %s", $expectedNumPhotos, count($elements));
            throw new ExpectationException($message, $this->getSession());
        }
    }

    /**
     * Chequea dentro de un container ( "data-behat"=$container) si existe la tabla que le pasamos dentro del "data-behat"=$attrData
     * @param unknown $table
     * @param unknown $container
     * @param unknown $attrData
     * @param string $dataName
     * @throws ExpectationException
     */
    private function iShouldSeeThisTableWithData($table, $container, $attrData, $dataName="data-behat")
    {
        if (!$container)
        {
            $container = $this->getSession()->getPage();
        }else{
            $container= $this->getSession()->getPage()->find('css', '*[data-behat="'.$container.'"]');
        }
        foreach ($table->getRows() as $row){
            $elementChannel = $container->find('css', '*['.$dataName.'="'.$attrData.'"]:contains("'.$row[0].'")');
            if(!$elementChannel){
                $message = sprintf("%s was not found", $row[0]);
                throw new ExpectationException($message, $this->getSession());
            }
        }
    }
    
    /**
     * @Then /^I should see the element with id "([^"]*)"$/
     */
    public function iShouldSeeTheElementWithId($arg1)
    {
    	$element = $this->getSession()->getPage()->find('css', '#'.$arg1);
    	if(!$element){
    		$message = sprintf("The element with id %s not found ", $arg1);
    		throw new ExpectationException($message, $this->getSession());
    	}
    	return $element;
    }
    
    /**
     * @Then /^I should see the element footer$/
     */
    public function iShouldSeeTheElementFooter()
    {
    	$element = $this->getSession()->getPage()->find('css', 'footer');
    	if(!$element){
    		$message = sprintf("The element footer not found ");
	   		throw new ExpectationException($message, $this->getSession());
    	}
    	return $element;
    }
    
    

    /**
     * Chequea que existe un elemento ($dataToCheck) del data ($dataName=$attrData)
     * @param string $dataName
     * @param string $attrData
     * @param string $dataToCheck
     * @throws ExpectationException
     * @return Ambigous <\Behat\Mink\Element\NodeElement, NULL>
     */
    protected function iShouldSeeTheFieldIntoData($attrData, $dataToCheck, $dataName="data-behat")
    {
        $element = $this->getSession()->getPage()->find('css', '*['.$dataName.'="'.$attrData.'"]:contains("'.$dataToCheck.'")');
        if(!$element){
            $message = sprintf("%s was not found in data %s = %s ", $dataToCheck, $dataName, $attrData);
            throw new ExpectationException($message, $this->getSession());
        }
        return $element;

    }
    /**
     * @Given /^I am logedin and go to index$/
     */
    public function iAmLogedinAndGoToIndex()
    {
    	$this->iAmLogedin();
    	$this->visit('/');
    }

    /**
     * @Given /^I am logedin$/
     */
    public function iAmLogedin()
    {
        $this->doInitFakeServer();

        $this->fakeServerMappings->addPostResource(
            '/oauth/v2/token',
            'fixtures/login/success_register_login.json',
            200,
            array(
                "grant_type" =>  "password",
                "client_id" => $this->kernel->getContainer()->getParameter("chatea_client_id"),
                "client_secret" => $this->kernel->getContainer()->getParameter("chatea_secret_id"),
                "username" => "ausername",
                "password" => "mysuperpassword"
            )
        );
        $this->beforeViewIndexList();

        $this->visit('/usuario/login');

        $this->fillField('username', 'ausername');
        $this->fillField('password', 'mysuperpassword');
        $this->pressButton('button-submit-login');
    }

    /**
     * @Given /^I click on the first photo$/
     */
    public function iClickOnTheFirstPhoto()
    {
        $element = $this->getSession()->getPage()->find('css', '[data-behat="photos"] figure a');
        $element->click();
    }

    /**
     * @Then /^show content$/
     */
    public function showContent()
    {
        die($this->getSession()->getPage()->getHtml());
    }
    
    /**
     * @Then /^The "([^"]*)" with tag "([^"]*)" exist$/
     */
    public function theWithTagExist($arg1, $arg2)
    {
    	$element = $this->getSession()->getPage()->find('css', '*['.$arg1.'="'.$arg2.'"]');
    	if(!$element){
    		$message = sprintf("%s='%s' was not found", $arg1, $arg2);
    		throw new ExpectationException($message, $this->getSession());
    	}
    	return $element;
    }
    
    /**
     * @Then /^The "([^"]*)" with tag "([^"]*)" not exist$/
     */
    public function theWithTagNotExist($arg1, $arg2)
    {
    	$element = $this->getSession()->getPage()->find('css', '*['.$arg1.'="'.$arg2.'"]');
    	if($element){
    		$message = sprintf("%s='%s' exist", $arg1, $arg2);
    		throw new ExpectationException($message, $this->getSession());
    	}
    	return $element;
    }
    
    /** Click on the element with the provided css query
     *
     * @When /^I click on the element with css "([^"]*)" and "([^"]*)"$/
     */
    public function iClickOnTheElementWithXPath($arg1, $arg2)
    {
    	$session = $this->getSession(); // get the mink session
    	
    	$element = $session->getPage()->find('css', '*['.$arg1.'="'.$arg2.'"]');
    	
    	// errors must not pass silently
    	if (null === $element) {
    		throw new \InvalidArgumentException(sprintf('Could not evaluate Css: "%s" = "%s"', $arg1, $arg2));
    	}
    
    	// ok, let's click on it
    	$element->click();
    
    }

    /**
     * @Given /^I shouuld se the user has (\d+) visits$/
     */
    public function iShouuldSeTheUserHasVisits($expectedNumberOfVisits)
    {
        $element = $this->getSession()->getPage()->find('css', '[data-behat="user_visits"]');

        $visits = trim($element->getText());

        if($visits !== $expectedNumberOfVisits){
            $message = sprintf("Expected %s visits but got %s visits", $expectedNumberOfVisits, $visits);
            throw new ExpectationException($message, $this->getSession());
        }
    }
}
