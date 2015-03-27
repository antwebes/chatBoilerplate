<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use AppBundle\Provider\Configuration;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
        return $this->render('default/index.html.twig');
    }

    public function logoAction()
    {

    	$parameters = Configuration::loadYml($this->getRequest());

    	$affiliate_name = "Project name";

    	if (array_key_exists("affiliate_name", $parameters)){
    		$affiliate_name =  $parameters['affiliate_name'];
    	}

        return $this->render('default/logo.html.twig', array('affiliate_name' => $affiliate_name));
    }

}
