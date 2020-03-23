<?php

namespace Deck\Domain\Table;

use Deck\Domain\Game\Player;
use Deck\Domain\User\PlayerId;

class TableReadModel
{
    /** @var TableId */
    private $id;
    /** @var PlayerId[] */
    private $players;

    public function __construct(
        TableId $tableId,
        array $players
    ) {
        $this->id = $tableId;

        /** @var Player $player */
        foreach ($players as $player) {
            $this->players[] = $player->playerId();
        }
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
}
