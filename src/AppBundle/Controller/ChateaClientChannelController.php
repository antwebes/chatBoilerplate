<?php

namespace AppBundle\Controller;

use Ant\Bundle\ChateaClientBundle\Controller\ChannelController;
use Ant\Bundle\ChateaClientBundle\Security\Authentication\Annotation\APIUser;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class ChateaClientChannelController extends ChannelController
{
    /**
     * @APIUser
     *
     */
    public function registerAction(Request $request)
    {
    	if ($this->getUser()){
    		if (!$this->getUser()->isValidated()){
    			throw new AccessDeniedException('Debes validar tu correo electrónico para poder registrar un canal.');
    		}
    	}
        return parent::registerAction($request);
    }

}
