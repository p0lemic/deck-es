<?php

declare(strict_types=1);

namespace Deck\Infrastructure\User\Auth;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class RequestListener implements EventSubscriberInterface {

    public function onKernelRequest(ResponseEvent $event)
    {
        $request = $event->getRequest();

        $request->attributes->set('refresh_token', $request->cookies->get('REFRESH_TOKEN'));
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST => [
                ['onKernelRequest']
            ]
        ];
    }
}
