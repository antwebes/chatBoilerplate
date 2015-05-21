<?php
/**
 * Created by PhpStorm.
 * User: ant4
 * Date: 14/05/15
 * Time: 15:35
 */

namespace AppBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\Container;


class CacheableContainer extends Container
{
    private static $cachedServices = array();
    private static $cachedServicesNames = array();

    public function clearCache()
    {
        self::$cachedServices = array();
    }

    public function get($id, $invalidBehavior = 1)
    {
        // if the id is not a service to be cached, call the parent and just return the service without caching it
        if(!in_array($id, self::$cachedServicesNames)){
            return $this->getParent($id, $invalidBehavior);
        }

        if(!isset(self::$cachedServices[$id]) || $id == 'request'){
            self::$cachedServices[$id] = $this->getParent($id, $invalidBehavior);
        }

        return self::$cachedServices[$id];
    }

    public function getParent($id, $invalidBehavior)
    {
        return parent::get($id, $invalidBehavior);
    }

    public function setServicesToCache($chachedServicesNames)
    {
        self::$cachedServicesNames = $chachedServicesNames;
    }
}