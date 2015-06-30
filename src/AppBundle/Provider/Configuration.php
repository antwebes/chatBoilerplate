<?php

namespace AppBundle\Provider;

use Symfony\Component\Yaml\Yaml;

class Configuration
{
    static function loadYml($request, $affiliate_dir)
    {
        $host = $request->getHost();
        $yml = self::loadConfigYmlFile($affiliate_dir);

        $parameters = Yaml::parse($yml);

        return $parameters;
    }

    static function loadConfigYmlFile($host)
    {
        $candidateFiles = array(
            __DIR__ . '/afiliates/'.$host.'.yml',
            __DIR__ . '/afiliates/default.yml',
            __DIR__ . '/afiliates/default.yml.dist'
        );

        $yml = false;

        foreach($candidateFiles as $candidateFile){
            $yml = @file_get_contents($candidateFile);

            if($yml !== false){
                return $yml;
            }
        }

        return $yml;
    }
}
