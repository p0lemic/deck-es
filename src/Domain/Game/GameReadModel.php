<?php

namespace Deck\Domain\Game;

use Deck\Domain\User\PlayerId;

class GameReadModel
{
    private GameId $id;
    /** @var PlayerId[] */
    private array $players;

    public function __construct(
        GameId $gameId,
        array $players
    ) {
        $this->id = $gameId;
        $this->players = $players;
    }

    public function id(): GameId
    {
        return $this->id;
    }

    /** @return PlayerId[] */
    public function players(): array
    {
        return $this->players;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id->value(),
            'players' => array_map(
                static fn (PlayerId $playerId) => $playerId->__toString(),
                $this->players,
            ),
        ];
    }
}
