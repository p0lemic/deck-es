<?php

namespace Deck\Domain\Game;

class GameReadModel
{
    /** @var GameId */
    private $id;
    /** @var Deck */
    private $deck;
    /** @var Player[] */
    private $players;

    public function __construct(
        GameId $gameId,
        array $players
    ) {
        $this->id = $gameId;

        /** @var Player $player */
        foreach ($players as $player) {
            $this->players[] = $player->playerId()->value();
        }
    }

    public function id(): GameId
    {
        return $this->id;
    }

    public function deck(): Deck
    {
        return $this->deck;
    }

    /** @return Player[] */
    public function players(): array
    {
        return $this->players;
    }

    public function joinPlayer(Player $player): void
    {
        $this->players[] = $player->playerId()->value();
    }
}
