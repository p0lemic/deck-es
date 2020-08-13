<?php

declare(strict_types=1);

namespace Deck\Domain\Table;

use Broadway\EventSourcing\EventSourcedAggregateRoot;
use Deck\Domain\Shared\ValueObject\DateTime;
use Deck\Domain\Table\Event\PlayerWasLeaved;
use Deck\Domain\Table\Event\PlayerWasSeated;
use Deck\Domain\Table\Event\TableWasCreated;
use Deck\Domain\Table\Exception\PlayerAlreadyInTable;
use Deck\Domain\Table\Exception\TableIsFull;
use Deck\Domain\User\PlayerId;

class Table extends EventSourcedAggregateRoot
{
    private const SIZE = 2;
    private TableId $id;
    /** @var PlayerId[] */
    private array $players;

    public static function create(TableId $tableId): self
    {
        $game = new self();
        $game->apply(new TableWasCreated($tableId, DateTime::now()));

        return $game;
    }

    /** @return PlayerId[] */
    public function players(): array
    {
        return $this->players;
    }

    public function playerLeaves(PlayerId $playerId): void
    {
        $this->apply(new PlayerWasLeaved($this->id, $playerId, DateTime::now()));
    }

    public function playerSits(PlayerId $playerId): void
    {
        $this->apply(new PlayerWasSeated($this->id, $playerId, DateTime::now()));
    }

    public function applyTableWasCreated(TableWasCreated $event): void
    {
        $this->id = $event->aggregateId();
        $this->players = [];
    }

    public function applyPlayerWasLeaved(PlayerWasLeaved $event): void
    {
        foreach ($this->players() as $key => $playerId) {
            if ($playerId === $event->playerId()) {
                unset($this->players[$key]);
            }
        }
    }

    /**
     * @param PlayerWasSeated $event
     *
     * @return void
     *
     * @throws PlayerAlreadyInTable|TableIsFull
     */
    public function applyPlayerWasSeated(PlayerWasSeated $event): void
    {
        if ($this->isFull()) {
            throw TableIsFull::isFull($event->playerId());
        }

        foreach ($this->players() as $player) {
            if ($event->playerId()->equals($player)) {
                throw PlayerAlreadyInTable::alreadyInTable($event->playerId());
            }
        }

        $this->players[] = $event->playerId();
    }

    public function isFull(): bool
    {
        return count($this->players) === self::SIZE;
    }

    public function getAggregateRootId(): string
    {
        return $this->id->value();
    }
}
