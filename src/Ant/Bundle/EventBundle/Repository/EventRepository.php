<?php

namespace Ant\Bundle\EventBundle\Repository;

use Ant\Bundle\EventBundle\Entity\Event;
use Doctrine\ORM\EntityRepository;

class EventRepository extends EntityRepository
{
    /**
     * Encuentra la event cuyo slug y city coinciden con los indicados.
     *
     * @param string $city El slug de la city
     * @param string $slug   El slug de la event
     *
     * @return Event|null
     */
    public function findEvent($city, $slug)
    {
        $em = $this->getEntityManager();

        $consulta = $em->createQuery('
            SELECT e, c
            FROM EventBundle:Event e JOIN e.city c
            WHERE e.revisada = true AND e.slug = :slug AND c.slug = :city
        ');
        $consulta->setParameter('slug', $slug);
        $consulta->setParameter('city', $city);
        $consulta->setMaxResults(1);

        return $consulta->getOneOrNullResult();
    }

    /**
     * Encuentra la event cuyo slug coinciden con los indicados.
     *
     * @param string $slug   El slug de la event
     *
     * @return Event|null
     */
    public function findEventBySlug($slug)
    {
        $em = $this->getEntityManager();

        $consulta = $em->createQuery('
            SELECT e
            FROM EventBundle:Event e
            WHERE e.revisada = true AND e.slug = :slug
        ');
        $consulta->setParameter('slug', $slug);
        $consulta->setMaxResults(1);

        return $consulta->getOneOrNullResult();
    }

    /**
     * Encuentra la event del día en la city indicada.
     *
     * @param string $city El slug de la city
     *
     * @return Event|null
     */
    public function findEventDelDia($city)
    {
        $em = $this->getEntityManager();

        $consulta = $em->createQuery('
            SELECT e, c, t
            FROM EventBundle:Event e JOIN e.city c JOIN e.tienda t
            WHERE e.revisada = true AND e.fechaPublicacion < :fecha AND c.slug = :city
            ORDER BY e.fechaPublicacion DESC
        ');
        $consulta->setParameter('fecha', new \DateTime('now'));
        $consulta->setParameter('city', $city);
        $consulta->setMaxResults(1);

        return $consulta->getOneOrNullResult();
    }

    /**
     * Encuentra la event del día de mañana en la city indicada.
     *
     * @param string $city El slug de la city
     *
     * @return Event|null
     */
    public function findEventDelDiaSiguiente($city)
    {
        $em = $this->getEntityManager();

        $consulta = $em->createQuery('
            SELECT e, c, t
            FROM EventBundle:Event e JOIN e.city c JOIN e.tienda t
            WHERE e.revisada = true AND e.fechaPublicacion < :fecha AND c.slug = :city
            ORDER BY e.fechaPublicacion DESC
        ');
        $consulta->setParameter('fecha', new \DateTime('tomorrow'));
        $consulta->setParameter('city', $city);
        $consulta->setMaxResults(1);

        return $consulta->getOneOrNullResult();
    }

    /**
     * Encuentra las cinco events más recuentes de la city indicada.
     *
     * @param int $cityId El id de la city
     *
     * @return array
     */
    public function findRecientesByCity($cityId)
    {
        $em = $this->getEntityManager();

        $consulta = $em->createQuery('
            SELECT e, t
            FROM EventBundle:Event e JOIN e.tienda t
            WHERE e.revisada = true AND e.fechaPublicacion < :fecha AND e.city = :id
            ORDER BY e.fechaPublicacion DESC
        ');
        $consulta->setMaxResults(5);
        $consulta->setParameter('id', $cityId);
        $consulta->setParameter('fecha', new \DateTime('today'));
        $consulta->useResultCache(true, 600);

        return $consulta->getResult();
    }

    /**
     * Encuentra las cinco events más recuentes de la city indicada.
     *
     * @return array
     */
    public function findRecientes()
    {
        $em = $this->getEntityManager();

        $consulta = $em->createQuery('
            SELECT e
            FROM EventBundle:Event e
            WHERE e.revisada = true AND e.fechaPublicacion < :fecha
            ORDER BY e.fechaPublicacion DESC
        ');
        $consulta->setMaxResults(5);
        $consulta->setParameter('fecha', new \DateTime('now'));
        $consulta->useResultCache(true, 600);

        return $consulta->getResult();
    }

    /**
     * Encuentra las cinco events más cercanas a la city indicada.
     *
     * @param string $city El slug de la city
     *
     * @return array
     */
    public function findCercanas($city)
    {
        $em = $this->getEntityManager();

        $consulta = $em->createQuery('
            SELECT e, c
            FROM EventBundle:Event e JOIN e.city c
            WHERE e.revisada = true AND e.fechaPublicacion <= :fecha AND c.slug != :city
            ORDER BY e.fechaPublicacion DESC
        ');
        $consulta->setMaxResults(5);
        $consulta->setParameter('city', $city);
        $consulta->setParameter('fecha', new \DateTime('today'));
        $consulta->useResultCache(true, 600);

        return $consulta->getResult();
    }

    /**
     * Encuentra todas las ventas de la event indicada.
     *
     * @param int $event El id de la event
     *
     * @return array
     */
    public function findVentasByEvent($event)
    {
        $em = $this->getEntityManager();

        $consulta = $em->createQuery('
            SELECT v, e, u
            FROM EventBundle:Venta v JOIN v.event e JOIN v.usuario u
            WHERE e.id = :id
            ORDER BY v.fecha DESC
        ');
        $consulta->setParameter('id', $event);

        return $consulta->getResult();
    }
}
