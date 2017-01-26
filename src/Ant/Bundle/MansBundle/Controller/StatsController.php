<?php

namespace Ant\Bundle\MansBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Stats controller.
 *
 */
class StatsController extends Controller
{
    public function generalStatsAction()
    {
        $pager = $this->get('api_users')->findAll(1);
        $num_users = $pager->count();
        
        $qb = $this->get('doctrine')->getEntityManager()->createQueryBuilder();
        $qb->select($qb->expr()->count('offer.id'));
        $qb->from('OfferBundle:Offer','offer');

        $num_offers = $qb->getQuery()->getSingleScalarResult();
        
        $qb = $this->get('doctrine')->getEntityManager()->createQueryBuilder();
        $qb->select($qb->expr()->count('parkingTicket.id'));
        $qb->from('ParkingBundle:ParkingTicket','parkingTicket');

        $num_parking_ticket = $qb->getQuery()->getSingleScalarResult();
        
        return $this->render('MansBundle:Stats:general.html.twig', array('num_offers' => $num_offers, 'num_users' => $num_users, 'num_parking_ticket' => $num_parking_ticket ));
    }
}