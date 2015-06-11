<?php

namespace AppBundle\Http;

use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route;

use AppBundle\Util\YamlFileLoader;


/**
 * Class ApiRequestAllow
 * @package Ant\CoreBundle\Http
 */
class ApiRequestAllow extends YamlFileLoader
{

    public function isAllow($uri){

        $matcher = new UrlMatcher($this->getRouteCollection(),new RequestContext());
      try{
          return $matcher->match($uri) != null;
      }catch (MethodNotAllowedException $e){
          return false;
      }catch (ResourceNotFoundException $e){
        return false;
      }

    }

    private function getRouteCollection()
    {
        $routes = new RouteCollection();
        $routesInfFile = $this->getParameters();
        $i=0;
        foreach($routesInfFile as $routeInfFile){
            $route = new Route($routeInfFile);
            $routes->add('route_name_'.$i++,$route);
        }
        return $routes;
    }
} 