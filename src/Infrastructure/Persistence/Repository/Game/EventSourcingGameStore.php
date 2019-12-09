<?php

declare(strict_types=1);

namespace Deck\Infrastructure\Persistence\Repository\Game;

use Broadway\EventHandling\EventBus;
use Broadway\EventSourcing\AggregateFactory\PublicConstructorAggregateFactory;
use Broadway\EventSourcing\EventSourcingRepository;
use Broadway\EventStore\EventStore;
use Deck\Domain\Game\Game;
use Deck\Domain\Game\GameRepositoryInterface;
use Deck\Domain\Shared\AggregateId;

final class EventSourcingGameStore extends EventSourcingRepository implements GameRepositoryInterface
{
    public function __construct(
        EventStore $eventStore,
        EventBus $eventBus,
        array $eventStreamDecorators = []
    ) {
        parent::__construct(
            $eventStore,
            $eventBus,
            Game::class,
            new PublicConstructorAggregateFactory(),
            $eventStreamDecorators
        );
    }

    public function store(Game $game): void
    {
        $this->save($game);
    }

    public function get(AggregateId $id): Game
    {
        /** @var Game $game */
        $game = $this->load($id->value());

        return $game;
    }
}
