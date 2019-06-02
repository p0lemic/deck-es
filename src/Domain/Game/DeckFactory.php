<?php

namespace Deck\Domain\Game;

class DeckFactory
{
    public function createNew() : Deck
    {
        return new Deck();
    }
}
