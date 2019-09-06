<?php

namespace Deck\Domain\Event;

class EventBuilder implements EventBuilderInterface
{
    /** @var Serializer */
    private $serializer;

    /**
     * EventBuilder constructor.
     * @param Serializer $serializer
     */
    public function __construct(Serializer $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * Serialize a DomainEvent and return an Event entity
     * @param DomainEvent $aDomainEvent
     * @return Event
     * @throws \Exception
     */
    public function build(DomainEvent $aDomainEvent): Event
    {
        $event = new Event(
            \get_class($aDomainEvent),
            $this->serializer->serialize($aDomainEvent),
            $aDomainEvent->occurredOn()
        );

        return $event;
    }
}
