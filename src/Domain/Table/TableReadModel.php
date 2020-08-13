<?php

namespace Deck\Domain\Table;

use Deck\Domain\User\PlayerId;
use function count;

class TableReadModel
{
    private const SIZE = 2;

    private TableId $id;
    /** @var PlayerId[] */
    private array $players;

    public function __construct(
        TableId $tableId
    ) {
        $this->id = $tableId;
        $this->players = [];
    }

    public function id(): TableId
    {
        return $this->id;
    }

    /** @return PlayerId[] */
    public function players(): array
    {
        return $this->players;
    }

    public function joinPlayer(PlayerId $playerId): void
    {
        $this->players[] = $playerId;
    }

    public function removePlayer(PlayerId $playerId): void
    {
        foreach ($this->players as $key => $player) {
            if ($player === $playerId) {
                unset($this->players[$key]);
            }
        }
    }

    public function isFull(): bool
    {
        return count($this->players) === self::SIZE;
    }
}
