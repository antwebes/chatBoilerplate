<?php

namespace AppBundle\Provider\Composer;

use Composer\Script\Event;
use Incenteev\ParameterHandler\Processor;

class ParameterGenerator
{
    public static function buildParameters(Event $event)
    {
        $configs = array(array('file' => __DIR__ . '/../afiliates/default.yml'));

        $processor = new Processor($event->getIO());

        foreach ($configs as $config) {
            if (!is_array($config)) {
                throw new \InvalidArgumentException('The extra.incenteev-parameters setting must be an array of configuration objects.');
            }

            $processor->processFile($config);
        }
    }
}