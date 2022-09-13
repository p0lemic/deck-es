<?php

namespace Deck\Infrastructure\Bus;

use Deck\Domain\Shared\DomainEvent;
use SimpleBus\SymfonyBridge\Bus\EventBus as SimpleEventBus;

class SimpleBusEventBus implements EventBus
{
    private SimpleEventBus $eventBus;

    public function __construct(
        SimpleEventBus $eventBus
    ) {
        $this->eventBus = $eventBus;
    }

    public function publish(?DomainEvent $singleEvent): void
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
