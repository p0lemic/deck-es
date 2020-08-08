<?php

namespace Deck\Domain\Table;

use Deck\Domain\User\PlayerId;
use function count;

class TableReadModel
{
    private const SIZE = 2;

    /** @var TableId */
    private $id;
    /** @var PlayerId[] */
    private $players;

    public function __construct(
        TableId $tableId,
        PlayerId $playerId
    ) {
        $this->id = $tableId;
        $this->players[] = $playerId;
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
