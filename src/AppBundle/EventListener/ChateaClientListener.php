<?php 
namespace AppBundle\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Ant\Bundle\ChateaClientBundle\Api\Model\Client;
use Ant\Bundle\ChateaClientBundle\Event\UserEvent;
use Ant\Bundle\ChateaClientBundle\Event\ChateaClientEvents;
use Ant\Bundle\ApiSocialBundle\Services\ParametersServiceInterface;

class ChateaClientListener implements EventSubscriberInterface
{
    /**
     * @var ParametersServiceInterface
     */
    public $parametersService;

    /**
     * ChateaClientListener constructor.
     *
     * @param ParametersServiceInterface $parametersService
     */
    public function __construct(ParametersServiceInterface $parametersService)
    {
        $this->parametersService = $parametersService;
    }


    public static function getSubscribedEvents()
    {
        return array(
			ChateaClientEvents::USER_REGISTER_SUCCESS => array('onUserRegisterSuccess', 0),
        );
    }

    public final function getClientId()
    {
        return $this->parametersService->getParameter(ParametersServiceInterface::PARAMETER_TYPE_CONTAINER,'client_id');
    }

    public final function getClientName()
    {
        return $this->parametersService->getParameter(ParametersServiceInterface::PARAMETER_TYPE_CONTAINER,'client_id');
    }

    public function onUserRegisterSuccess(UserEvent $event)
    {
        $client = new Client();
        $client->setId(getClientId());
        $event->getUser()->setClient($client);
    }
}