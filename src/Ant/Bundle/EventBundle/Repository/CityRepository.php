<?php

namespace Ant\Bundle\EventBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;

class CityRepository extends EntityRepository
{
    /**
     * Devuelve un array simple con todas las ciudades disponibles.
     *
     * @return array
     */
    public function findListaCiudades()
    {
        $em = $this->getEntityManager();

        $consulta = $em->createQuery('
            SELECT c
            FROM Ant\Bundle\EventBundle:Ciudad c
            ORDER BY c.nombre
        ');
        $consulta->useResultCache(true, 3600);

        return $consulta->getArrayResult();
    }

    /**
     * Encuentra las cinco ciudades más cercanas a la ciudad indicada.
     *
     * @param int $ciudadId El id de la ciudad para la que se buscan cercanas
     *
     * @return array
     */
    public function findCercanas($ciudadId)
    {
        $em = $this->getEntityManager();

        $consulta = $em->createQuery('
            SELECT c
            FROM Ant\Bundle\EventBundle:Ciudad c
            WHERE c.id != :id
            ORDER BY c.nombre ASC
        ');
        $consulta->setMaxResults(5);
        $consulta->setParameter('id', $ciudadId);
        $consulta->useResultCache(true, 3600);

        return $consulta->getResult();
    }

    /**
     * Encuentra todas las events de la ciudad indicada.
     *
     * @param string $ciudad El slug de la ciudad para la que se buscan sus events
     *
     * @return array
     */
    public function findTodasLasEvents($ciudad)
    {
        return $this->queryTodasLasEvents($ciudad)->getResult();
    }

    /**
     * Método especial asociado con `findTodasLasEvents()` y que devuelve solamente
     * la consulta necesaria para obtener todas las events de la ciudad indicada.
     * Se utiliza para la paginación de resultados.
     *
     * @param string $ciudad El slug de la ciudad
     *
     * @return Query
     */
    public function queryTodasLasEvents($ciudad)
    {
        $em = $this->getEntityManager();

        $consulta = $em->createQuery('
            SELECT o, t
            FROM Ant\Bundle\EventBundle:Oferta o JOIN o.tienda t JOIN o.ciudad c
            WHERE c.slug = :ciudad
            ORDER BY o.fechaPublicacion DESC
        ');
        $consulta->setParameter('ciudad', $ciudad);
        $consulta->useResultCache(true, 600);

        return $consulta;
    }

    /**
     * Encuentra todos los usuarios asociados a la ciudad indicada.
     *
     * @param string $ciudad El slug de la ciudad para la que se buscan sus usuarios
     *
     * @return array
     */
    public function findTodosLosUsuarios($ciudad)
    {
        return $this->queryTodosLosUsuarios($ciudad)->getResult();
    }

    /**
     * Método especial asociado con `findTodosLosUsuarios()` y que devuelve solamente
     * la consulta necesaria para obtener todos los usuarios de la ciudad indicada.
     * Se utiliza para la paginación de resultados.
     *
     * @param string $ciudad El slug de la ciudad
     *
     * @return Query
     */
    public function queryTodosLosUsuarios($ciudad)
    {
        $em = $this->getEntityManager();

        $consulta = $em->createQuery('
            SELECT u
            FROM UsuarioBundle:Usuario u JOIN u.ciudad c
            WHERE c.slug = :ciudad
            ORDER BY u.apellidos ASC
        ');
        $consulta->setParameter('ciudad', $ciudad);

        return $consulta;
    }

    /**
     * Encuentra todas las tiendas asociadas a la ciudad indicada.
     *
     * @param string $ciudad El slug de la ciudad para la que se buscan sus tiendas
     *
     * @return array
     */
    public function findTodasLasTiendas($ciudad)
    {
        return $this->queryTodasLasTiendas($ciudad)->getResult();
    }

    /**
     * Método especial asociado con `findTodasLasTiendas()` y que devuelve solamente
     * la consulta necesaria para obtener todas las tiendas de la ciudad indicada.
     * Se utiliza para la paginación de resultados.
     *
     * @param string $ciudad El slug de la ciudad
     *
     * @return Query
     */
    public function queryTodasLasTiendas($ciudad)
    {
        $em = $this->getEntityManager();

        $consulta = $em->createQuery('
            SELECT t
            FROM Ant\Bundle\EventBundle:Tienda t JOIN t.ciudad c
            WHERE c.slug = :ciudad
            ORDER BY t.nombre ASC
        ');
        $consulta->setParameter('ciudad', $ciudad);
        $consulta->useResultCache(true, 600);

        return $consulta;
    }
}
