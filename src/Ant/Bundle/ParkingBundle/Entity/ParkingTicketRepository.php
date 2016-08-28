<?php

namespace Ant\Bundle\ParkingBundle\Entity;

use Doctrine\ORM\EntityRepository;

class ParkingTicketRepository extends EntityRepository
{
    public function findAllParkingTicketFromToday()
    {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT pt FROM ParkingBundle:ParkingTicket pt WHERE pt.endDate >= :today ORDER BY pt.startDate ASC'
            )->setParameter('today', new \DateTime())
            ->getResult();
    }
}