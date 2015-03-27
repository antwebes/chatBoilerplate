<?php 
namespace AppBundle\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use AppBundle\Provider\Configuration;

use Ant\Bundle\ChateaClientBundle\Api\Model\Client;
use Ant\Bundle\ChateaClientBundle\Event\UserEvent;

class ChateaClientListener implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return array(
            'user.register.success' => array('onUserRegisterSuccess', 0),
        );
    }


    public function onUserRegisterSuccess(UserEvent $event)
    {
        $parameters = Configuration::loadYml($event->getRequest());

        $client = new Client();
        $client->setId($parameters['client']);
        $event->getUser()->setClient($client);
    }
}