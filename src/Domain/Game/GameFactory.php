<?php

namespace Deck\Domain\Game;

class GameFactory
{
    /** @var DeckFactory */
    private $deckFactory;

    public function __construct(DeckFactory $deckFactory)
    {
        $this->deckFactory = $deckFactory;
    }

    public function createNewGame(array $players) : Game
    {
        return new Game($this->deckFactory, $players);
    }
}
