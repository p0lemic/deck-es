<?php

namespace Deck\Infrastructure\Events;

use SimpleBus\SymfonyBridge\Bus\EventBus as SimpleEventBus;

class SimpleBusEventBus implements EventBus
{
    private SimpleEventBus $eventBus;

    public function __construct(
        SimpleEventBus $eventBus
    ) {
        $this->eventBus = $eventBus;
    }

    public function publish($singleEvent): void
    {
        if (null === $singleEvent) {
            return;
        }

        $this->eventBus->handle($singleEvent);
    }

    public function publishEvents(array $events): void
    {
        foreach ($events as $singleEvent) {
            $this->publish($singleEvent);
        }
    }
}
