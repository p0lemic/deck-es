<?php

namespace Deck\Domain\Game;

use Deck\Domain\Game\Exception\InvalidPlayerNumber;

class GameFactory
{
    /**
     * @param Player[] $players
     * @return Game
     * @throws InvalidPlayerNumber
     */
    public function createNewGame(array $players): Game
    {
        $totalPlayers = count($players);

        if ($totalPlayers <= 0) {
            throw InvalidPlayerNumber::biggerThanZero();
        }

        return Game::create($players);
    }
}
