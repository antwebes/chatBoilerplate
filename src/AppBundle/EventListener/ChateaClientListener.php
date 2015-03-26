<?php 
namespace AppBundle\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Yaml\Yaml;

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
        $host = $event->getRequest()->getHost();

        if (strpos($host,'chatzona') !== false) {
           $parameters = Yaml::parse(file_get_contents(__DIR__ . '/chatzona.yml'));
        }else {
           $parameters = Yaml::parse(file_get_contents(__DIR__ . '/default.yml'));
        }

        $client = new Client();
        $client->setId($parameters['client']);
        $event->getUser()->setClient($client);
    }
}