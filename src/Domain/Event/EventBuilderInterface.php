<?php

namespace Deck\Domain\Event;

interface EventBuilderInterface
{
    /**
     * Serialize a Domain Event and Create an Event
     * @param DomainEvent $aDomainEvent
     * @return Event
     */
    public function build(DomainEvent $aDomainEvent) : Event;

}
