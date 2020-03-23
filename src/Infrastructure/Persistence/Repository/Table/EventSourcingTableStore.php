<?php

declare(strict_types=1);

namespace Deck\Infrastructure\Persistence\Repository\Table;

use Broadway\EventHandling\EventBus;
use Broadway\EventSourcing\AggregateFactory\PublicConstructorAggregateFactory;
use Broadway\EventSourcing\EventSourcingRepository;
use Broadway\EventStore\EventStore;
use Deck\Domain\Shared\AggregateId;
use Deck\Domain\Table\Table;
use Deck\Domain\Table\TableRepositoryInterface;

final class EventSourcingTableStore extends EventSourcingRepository implements TableRepositoryInterface
{
    public function __construct(
        EventStore $eventStore,
        EventBus $eventBus,
        array $eventStreamDecorators = []
    ) {
        parent::__construct(
            $eventStore,
            $eventBus,
            Table::class,
            new PublicConstructorAggregateFactory(),
            $eventStreamDecorators
        );
    }

    public function store(Table $table): void
    {
        $this->save($table);
    }

    public function get(AggregateId $id): Table
    {
        /** @var Table $table */
        $table = $this->load($id->value());

        return $table;
    }
}
