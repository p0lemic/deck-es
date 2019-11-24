<?php

declare(strict_types=1);

namespace Deck\Infrastructure\Persistence\Repository\User;

use Broadway\EventHandling\EventBus;
use Broadway\EventSourcing\AggregateFactory\PublicConstructorAggregateFactory;
use Broadway\EventSourcing\EventSourcingRepository;
use Broadway\EventStore\EventStore;
use Deck\Domain\Aggregate\AggregateId;
use Deck\Domain\User\Player;
use Deck\Domain\User\PlayerRepositoryInterface;

final class EventSourcingPlayerStore extends EventSourcingRepository implements PlayerRepositoryInterface
{
    public function __construct(
        EventStore $eventStore,
        EventBus $eventBus,
        array $eventStreamDecorators = []
    ) {
        parent::__construct(
            $eventStore,
            $eventBus,
            Player::class,
            new PublicConstructorAggregateFactory(),
            $eventStreamDecorators
        );
    }

    public function store(Player $player): void
    {
        $this->save($player);
    }

    public function get(AggregateId $id): Player
    {
        /** @var Player $player */
        $player = $this->load($id->value()->toString());

        return $player;
    }
}
