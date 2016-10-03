<?php

namespace Ant\Bundle\OfferBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Ant\Bundle\OfferBundle\Entity\Offer;
use Ant\Bundle\OfferBundle\Form\OfferType;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * Contact controller.
 *
 */
class ContactController extends Controller
{
    /**
     * Page to send offers
     *
     */
    public function contactAction()
    {
        return $this->render('OfferBundle:Contact:contact.html.twig');
    }
}
