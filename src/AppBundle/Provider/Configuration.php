<?php

namespace AppBundle\Provider;

use Symfony\Component\Yaml\Yaml;

class Configuration
{
    static function loadYml($request)
    {
        $host = $request->getHost();

        $yml = false;
        
        if (strpos($host,'chatzona') !== false) {
        	$yml = @file_get_contents(__DIR__ . '/afiliates/chatzona.yml');
        }

        if($yml === FALSE){
        	$yml = @file_get_contents(__DIR__ . '/afiliates/default.yml');
        }

        $parameters = Yaml::parse($yml);

        return $parameters;
    }
}
