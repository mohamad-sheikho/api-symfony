<?php

namespace App\EventSubscriber;

use http\Env\Response;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Http\Event\LogoutEvent;

class LogoutSubscriber implements EventSubscriberInterface
{
    public function onLogoutEvent($event): void
    {
        if (in_array('application/json', $event->getRequest()->getAcceptableContentTypes())){
            $event->setResponse(new JsonResponse(null, \Symfony\Component\HttpFoundation\Response::HTTP_NO_CONTENT ));

        }
    }

    public static function getSubscribedEvents(): array
    {
        return [
            LogoutEvent::class => 'onLogoutEvent',
        ];
    }
}
