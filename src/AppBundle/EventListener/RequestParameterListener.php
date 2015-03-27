<?php
namespace AppBundle\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use AppBundle\Provider\Configuration;

use Ant\Bundle\ChateaClientBundle\Api\Model\Client;
use Ant\Bundle\ChateaClientBundle\Event\UserEvent;

class RequestParameterListener
{
    private $twig;

    public function __construct($twig)
    {
        $this->twig = $twig;
    }

    public function onKernelRequest($event)
    {
        $parameters = Configuration::loadYml($event->getRequest());
        foreach ($parameters as $clave => $valor) {
            $this->twig->addGlobal($clave, $valor);
        }

    }
}