<?php

declare(strict_types=1);

namespace Deck\Infrastructure\Persistence\Repository\Deck;

use Broadway\EventHandling\EventBus;
use Broadway\EventSourcing\AggregateFactory\PublicConstructorAggregateFactory;
use Broadway\EventSourcing\EventSourcingRepository;
use Broadway\EventStore\EventStore;

class DeckRepository extends EventSourcingRepository
{
    public function __construct(EventStore $eventStore, EventBus $eventBus)
    {
        parent::__construct($eventStore, $eventBus, 'Deck', new PublicConstructorAggregateFactory());
    }
}
