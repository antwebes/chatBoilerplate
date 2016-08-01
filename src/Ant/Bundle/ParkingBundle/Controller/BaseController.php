<?php

namespace Ant\Bundle\ParkingBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Ant\Bundle\ParkingBundle\Entity\Parking;
use Ant\Bundle\ParkingBundle\Form\ParkingType;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class BaseController extends Controller
{
    protected function assertPermissions()
    {
        if (!$this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw new AccessDeniedException();
        }
    }

    protected function getUserOnline()
    {
        if (!$this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return null;
        }

        $user = $this->getUser();

        return $user;
    }
}
