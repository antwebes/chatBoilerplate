<?php 
namespace AppBundle\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use AppBundle\Provider\Configuration;

use Ant\Bundle\ChateaClientBundle\Api\Model\Client;
use Ant\Bundle\ChateaClientBundle\Event\UserEvent;

class ChateaClientListener implements EventSubscriberInterface
{
    public $affiliate_path;

    public function __construct($affiliate_path)
    {
        $this->affiliate_path = $affiliate_path;
    }


    public static function getSubscribedEvents()
    {
        return array(
            'user.register.success' => array('onUserRegisterSuccess', 0),
        );
    }


    public function onUserRegisterSuccess(UserEvent $event)
    {
        $parameters = Configuration::loadYml($event->getRequest(),$this->affiliate_path);

        $client = new Client();
        $client->setId($parameters['client']);
        $event->getUser()->setClient($client);
    }
}