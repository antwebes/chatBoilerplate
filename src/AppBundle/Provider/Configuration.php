<?php

namespace AppBundle\Provider;

use Symfony\Component\Yaml\Yaml;

class Configuration
{
    static function loadYml($request, $affiliate_dir)
    {
        $host = $request->getHost();
        return self::loadConfig($affiliate_dir, $host);
    }

    static function loadConfig($affiliate_dir, $host)
    {
        $configs = array_merge(
            self::loadFistAviaibleYml(array($affiliate_dir.'default.yml', __DIR__ . '/afiliates/default.yml')),
            self::loadFistAviaibleYml(array($affiliate_dir.$host.'.yml', __DIR__ . '/afiliates/'.$host.'.yml'))
        );

        if(count($configs) == 0){
            $candidateFiles = array(
                $affiliate_dir.'default.yml',
                __DIR__.'/afiliates/default.yml',
                $affiliate_dir.$host.'.yml',
                __DIR__.'/afiliates/'.$host.'.yml'
            );

            $message = sprintf("There are no configuration parameters for this host. Create one of the following files: %s",
                implode(", ", $candidateFiles));

            throw new \Exception($message);
        }

        return $configs;
    }

    static function loadFistAviaibleYml($candidateFiles)
    {
        foreach($candidateFiles as $candidateFile){
            $yml = @file_get_contents($candidateFile);

            if($yml !== false){
                return Yaml::parse($yml)['parameters'];
            }
        }

        return array();
    }
}
