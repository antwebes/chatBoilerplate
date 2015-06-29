<?php

namespace AppBundle\Provider;

use Symfony\Component\Yaml\Yaml;

class Configuration
{
    static function loadYml($request, $affiliate_dir)
    {
        $host = $request->getHost();
        $yml = false;

        $yml = @file_get_contents(__DIR__ ."/".$affiliate_dir.$host.'.yml');

        if($yml === FALSE && !is_null($affiliate_dir)){
        	$yml = @file_get_contents(__DIR__ . "/".$affiliate_dir. "/default.yml");
        }

        if($yml === FALSE){
        	$yml = @file_get_contents(__DIR__ . '/afiliates/default.yml');
        }

        $parameters = Yaml::parse($yml);

        return $parameters;
    }
}
