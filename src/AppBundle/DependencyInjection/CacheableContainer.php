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

    public function clearCache()
    {
        self::$cachedServices = array();
    }

    public function get($id, $invalidBehavior = 1)
    {
        if(!isset(self::$cachedServices[$id]) || $id == 'request'){
            self::$cachedServices[$id] = $this->getParent($id, $invalidBehavior);
        }

        return self::$cachedServices[$id];
    }

    public function getParent($id, $invalidBehavior)
    {
        return parent::get($id, $invalidBehavior);
    }
}