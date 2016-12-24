<?php

namespace Ant\Bundle\EventBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Response;

class CityController extends Controller
{
    /**
     * @Cache(smaxage="60")
     *
     * Muestra la página de detalle del evento indicada.
     *
     * @param string $city El slug de la city a la que pertenece la evento
     * @param string $slug   El slug del evento (es único en cada city)
     *
     * @return Response
     *
     * @throws NotFoundHttpException
     */
    public function showAction($city, $slug)
    {
        $em = $this->getDoctrine()->getManager();

        $event = $em->getRepository('EventBundle:Event')->findEvent($city, $slug);
        $cercanas = $em->getRepository('EventBundle:Event')->findCercanas($city);

        if (!$event) {
            throw $this->createNotFoundException('No se ha encontrado el evento solicitada');
        }

        return $this->render('EventBundle:Event:show.html.twig', array(
            'cercanas' => $cercanas,
            'event' => $event,
        ));
    }
}
