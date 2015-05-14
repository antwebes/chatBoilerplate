<?php
namespace AppBundle\Features\Context;

use Ant\Bundle\GuzzleFakeServer\Behat\Context\Context as BaseContext;
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
        
        $this->fakeServerMappings->addPostResource(
            '/oauth/v2/token',
            'fixtures/login/success_register_login.json',
            200,
            array(
                "username" => "ausername",
                "password" => "mysuperpass"
            )
        );
        $this->fakeServerMappings->addPostResource(
            '/api/register',
            'fixtures/users/register.json'
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
     * @Then /^show content$/
     */
    public function showContent()
    {
        die($this->getSession()->getPage()->getHtml());
    }
}