<?php

declare(strict_types=1);

namespace Deck\Domain\Table;

use Broadway\EventSourcing\EventSourcedAggregateRoot;
use Deck\Domain\Shared\ValueObject\DateTime;
use Deck\Domain\Table\Event\PlayerWasLeaved;
use Deck\Domain\Table\Event\PlayerWasSeated;
use Deck\Domain\Table\Event\TableWasCreated;
use Deck\Domain\Table\Event\TableWasFilled;
use Deck\Domain\Table\Exception\PlayerAlreadyInTable;
use Deck\Domain\User\PlayerId;
use function in_array;

class Table extends EventSourcedAggregateRoot
{
    private const SIZE = 2;
    /** @var TableId */
    private $id;
    /** @var PlayerId[] */
    private $players;

    public static function create(PlayerId $playerId): self
    {
        $game = new self();
        $game->apply(new TableWasCreated(TableId::create(), $playerId, DateTime::now()));

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

        if ($this->isFull()) {
            $this->apply(new TableWasFilled($this->id, $this->players(), DateTime::now()));
        }
    }

    public function applyTableWasCreated(TableWasCreated $event): void
    {
        $this->id = $event->aggregateId();
        $this->players[] = $event->playerId();
    }

    public function applyPlayerWasLeaved(PlayerWasLeaved $event): void
    {
        foreach ($this->players() as $key => $playerId) {
            if ($playerId === $event->playerId()) {
                unset($this->players[$key]);
            }
        }
    }

    public function applyPlayerWasSeated(PlayerWasSeated $event): void
    {
        if (in_array($event->playerId(), $this->players(), true)) {
            throw PlayerAlreadyInTable::alreadyInTable($event->playerId());
        }

        if ($this->isFull()) {
            return;
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
