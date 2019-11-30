<?php

namespace Deck\Domain\Game;

use Deck\Application\Game\Exception\InvalidPlayerNumber;

class GameFactory
{
    /** @var DeckFactory */
    private $deckFactory;

    public function __construct(DeckFactory $deckFactory)
    {
        $this->deckFactory = $deckFactory;
    }

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

//        if ($totalPlayers !== 2) {
//            throw InvalidPlayerNumber::equalsToTwo();
//        }

        return Game::create($this->deckFactory->createNew(), $players);
    }
}
