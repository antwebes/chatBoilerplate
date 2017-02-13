<?php

namespace Ant\Bundle\EventBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class EventController extends Controller
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
    public function showAction($slug)
    {
        $em = $this->getDoctrine()->getManager();

        $event = $em->getRepository('EventBundle:Event')->findEventBySlug($slug);

        if (!$event) {
            throw $this->createNotFoundException('No se ha encontrado el evento solicitada');
        }
        if ($event->isPrivate()){
             if (false === $this->get('security.context')->isGranted('ROLE_MANS')) {
                 throw $this->createAccessDeniedException('Se trata de un evento solo para usuarios de Mans');
             }
        }

        return $this->render('EventBundle:Event:show.html.twig', array(
            'event' => $event,
        ));
    }
    
    /**
     * @Cache(smaxage="3600")
     *
     * Muestra los eventos más recientes
     *
     * @param Request $request
     *
     * @return Response
     *
     * @throws NotFoundHttpException
     */
    public function listAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $events = $em->getRepository('EventBundle:Event')->findRecientes();

        return $this->render('EventBundle:Event:list.html.twig', array(
            'events' => $events,
        ));
    }
}
