<?php
namespace AppBundle\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use AppBundle\Provider\Configuration;

use Ant\Bundle\ChateaClientBundle\Api\Model\Client;
use Ant\Bundle\ChateaClientBundle\Event\UserEvent;

class RequestParameterListener
{
    private $twig;
    private $affiliate_dir;

    public function __construct($twig, $affiliate_dir)
    {
        $this->twig = $twig;
        $this->affiliate_dir = $affiliate_dir;
    }

    public function onKernelRequest($event)
    {
        $parameters = Configuration::loadYml($event->getRequest(),$this->affiliate_dir);
        foreach ($parameters as $clave => $valor) {
            $this->twig->addGlobal($clave, $valor);
        }

    }
}