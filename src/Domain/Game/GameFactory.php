<?php

namespace Deck\Domain\Game;

use Deck\Domain\Game\Exception\InvalidPlayerNumber;
use Deck\Domain\User\PlayerId;

class GameFactory
{
    /**
     * @param GameId $gameId
     * @param DeckId $deckId
     * @param PlayerId[] $players
     * @return Game
     * @throws InvalidPlayerNumber
     */
    public function createNewGame(GameId $gameId, DeckId $deckId, array $players): Game
    {
        $totalPlayers = count($players);

        if ($totalPlayers < 2) {
            throw InvalidPlayerNumber::equalsToTwo();
        }

        return Game::create($gameId, $deckId, $players);
    }
}
