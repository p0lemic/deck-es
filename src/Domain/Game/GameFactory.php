<?php

namespace Deck\Domain\Game;

use Deck\Domain\Deck\DeckFactory;

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
        return new Game(GameId::create(), $this->deckFactory, $players);
    }
}
